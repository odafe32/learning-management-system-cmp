<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Course;

class AssignmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $assignmentId = $this->route('assignment') ? $this->route('assignment')->id : null;

        return [
            'course_id' => [
                'required',
                'exists:courses,id',
                function ($attribute, $value, $fail) {
                    // Ensure the course belongs to the authenticated instructor
                    $course = Course::find($value);
                    if (!$course || $course->user_id !== auth()->id()) {
                        $fail('The selected course is invalid or does not belong to you.');
                    }
                }
            ],
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('assignments', 'title')
                    ->ignore($assignmentId)
                    ->where('course_id', $this->course_id)
            ],
            'description' => 'nullable|string|max:5000',
            'code_sample' => 'nullable|string|max:50000',
            'deadline' => [
                'required',
                'date',
                'after:now'
            ],
            'status' => [
                'required',
                'string',
                Rule::in(['active', 'draft', 'archived'])
            ],
        ];
    }

    /**
     * Get custom error messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'course_id.required' => 'Please select a course for this assignment.',
            'course_id.exists' => 'The selected course is invalid.',
            'title.required' => 'Assignment title is required.',
            'title.unique' => 'An assignment with this title already exists in the selected course.',
            'deadline.required' => 'Assignment deadline is required.',
            'deadline.after' => 'Assignment deadline must be in the future.',
            'status.required' => 'Assignment status is required.',
            'status.in' => 'Invalid assignment status selected.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'course_id' => 'course',
            'code_sample' => 'code sample',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Convert deadline to proper format if needed
        if ($this->has('deadline_date') && $this->has('deadline_time')) {
            $this->merge([
                'deadline' => $this->deadline_date . ' ' . $this->deadline_time
            ]);
        }
    }
}