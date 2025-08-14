<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Assignment;

class SubmissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $submissionId = $this->route('submission') ? $this->route('submission')->id : null;

        return [
            'assignment_id' => [
                'required',
                'exists:assignments,id',
                function ($attribute, $value, $fail) {
                    $assignment = Assignment::find($value);
                    if (!$assignment) {
                        $fail('The selected assignment is invalid.');
                        return;
                    }

                    // Check if assignment is active
                    if ($assignment->status !== 'active') {
                        $fail('This assignment is not currently accepting submissions.');
                        return;
                    }

                    // Check if deadline has passed (allow some grace period)
                    if ($assignment->deadline < now()->subMinutes(5)) {
                        $fail('The submission deadline has passed.');
                        return;
                    }
                }
            ],
            'code_content' => 'required|string|max:100000',
            'file' => [
                'nullable',
                'file',
                'mimes:txt,py,js,java,php,cpp,c,cs,html,css,sql,json,xml',
                'max:10240' // 10MB
            ],
            'status' => [
                'required',
                'string',
                Rule::in(['draft', 'submitted'])
            ],
        ];
    }

    /**
     * Get custom error messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'assignment_id.required' => 'Assignment is required.',
            'assignment_id.exists' => 'The selected assignment is invalid.',
            'code_content.required' => 'Code content is required.',
            'code_content.max' => 'Code content cannot exceed 100,000 characters.',
            'file.mimes' => 'File must be a valid code file (txt, py, js, java, php, cpp, c, cs, html, css, sql, json, xml).',
            'file.max' => 'File size cannot exceed 10MB.',
            'status.required' => 'Submission status is required.',
            'status.in' => 'Invalid submission status.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'assignment_id' => 'assignment',
            'code_content' => 'code content',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Set submitted_at timestamp if status is submitted
        if ($this->status === 'submitted' && !$this->submitted_at) {
            $this->merge([
                'submitted_at' => now()
            ]);
        }
    }
}