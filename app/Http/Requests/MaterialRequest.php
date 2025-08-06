<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Material;

class MaterialRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Check if user is authenticated and has lecturer role (since your DB uses 'lecturer')
        return auth()->check() && auth()->user()->hasRole('lecturer');
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'visibility' => 'required|in:public,enrolled,private',
        ];

        // File is required for store, optional for update
        if ($this->isMethod('post')) {
            $rules['file'] = [
                'required',
                'file',
                'max:' . (Material::getMaxFileSize()), // Max size in KB
                'mimes:' . implode(',', Material::getAllowedFileTypes())
            ];
        } else {
            $rules['file'] = [
                'nullable',
                'file',
                'max:' . (Material::getMaxFileSize()),
                'mimes:' . implode(',', Material::getAllowedFileTypes())
            ];
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        $maxSizeMB = Material::getMaxFileSize() / 1024;
        $allowedTypes = implode(', ', Material::getAllowedFileTypes());

        return [
            'course_id.required' => 'Please select a course.',
            'course_id.exists' => 'The selected course is invalid.',
            'title.required' => 'Material title is required.',
            'title.max' => 'Material title cannot exceed 255 characters.',
            'description.max' => 'Description cannot exceed 1000 characters.',
            'visibility.required' => 'Please select visibility option.',
            'visibility.in' => 'Invalid visibility option selected.',
            'file.required' => 'Please select a file to upload.',
            'file.file' => 'The uploaded file is invalid.',
            'file.max' => "File size cannot exceed {$maxSizeMB}MB.",
            'file.mimes' => "Only the following file types are allowed: {$allowedTypes}.",
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'course_id' => 'course',
            'file' => 'material file',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Additional validation: Check if the course belongs to the authenticated lecturer
            if ($this->course_id) {
                $course = \App\Models\Course::find($this->course_id);
                if ($course && $course->user_id !== auth()->id()) {
                    $validator->errors()->add('course_id', 'You can only upload materials to your own courses.');
                }
            }
        });
    }
}