<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CourseRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $courseId = $this->route('course') ? $this->route('course')->id : null;

        return [
            'title' => ['required', 'string', 'max:255'],
            'code' => [
                'required', 
                'string', 
                'max:20',
                Rule::unique('courses')->ignore($courseId)
            ],
            'description' => ['nullable', 'string', 'max:1000'],
            'level' => ['nullable', 'in:100,200,300,400,500'],
            'semester' => ['nullable', 'in:first,second'],
            'status' => ['required', 'in:active,inactive,draft'],
            'credit_units' => ['required', 'integer', 'min:1', 'max:6'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Course title is required.',
            'title.max' => 'Course title cannot exceed 255 characters.',
            'code.required' => 'Course code is required.',
            'code.unique' => 'This course code is already taken.',
            'code.max' => 'Course code cannot exceed 20 characters.',
            'description.max' => 'Description cannot exceed 1000 characters.',
            'level.in' => 'Please select a valid level.',
            'semester.in' => 'Please select a valid semester.',
            'status.required' => 'Course status is required.',
            'status.in' => 'Please select a valid status.',
            'credit_units.required' => 'Credit units is required.',
            'credit_units.integer' => 'Credit units must be a number.',
            'credit_units.min' => 'Credit units must be at least 1.',
            'credit_units.max' => 'Credit units cannot exceed 6.',
            'image.image' => 'Course image must be an image file.',
            'image.mimes' => 'Course image must be a JPEG, PNG, JPG, or GIF file.',
            'image.max' => 'Course image size cannot exceed 2MB.',
        ];
    }
}