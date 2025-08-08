<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Course;
use Illuminate\Support\Facades\Log;

class CourseRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $courseId = $this->route('course') ? $this->route('course')->id : null;
        $userId = auth()->id();

        return [
            'title' => [
                'required', 
                'string', 
                'max:255',
                'min:3'
            ],
            'code' => [
                'required', 
                'string', 
                'max:20',
                'regex:/^[A-Z]{2,4}[0-9]{3,4}$/',
                Rule::unique('courses')
                    ->where('user_id', $userId)
                    ->ignore($courseId)
            ],
            'description' => [
                'nullable', 
                'string', 
                'max:1000'
            ],
            'level' => [
                'required',
                Rule::in(array_keys(Course::getLevels()))
            ],
            'semester' => [
                'required',
                Rule::in(array_keys(Course::getSemesters()))
            ],
            'status' => [
                'required',
                Rule::in(array_keys(Course::getStatuses()))
            ],
            'credit_units' => [
                'required', 
                'integer', 
                'min:1', 
                'max:6'
            ],
            'image' => [
                'nullable', 
                'image', 
                'mimes:jpeg,png,jpg,gif', 
                'max:5120' // 5MB in KB
            ],
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Course title is required.',
            'title.min' => 'Course title must be at least 3 characters long.',
            'title.max' => 'Course title cannot exceed 255 characters.',
            
            'code.required' => 'Course code is required.',
            'code.regex' => 'Course code must be in format like CSC101, MATH201, etc. (2-4 letters followed by 3-4 numbers).',
            'code.unique' => 'You already have a course with this code.',
            'code.max' => 'Course code cannot exceed 20 characters.',
            
            'description.max' => 'Description cannot exceed 1000 characters.',
            
            'level.required' => 'Course level is required.',
            'level.in' => 'Please select a valid course level.',
            
            'semester.required' => 'Semester is required.',
            'semester.in' => 'Please select a valid semester.',
            
            'status.required' => 'Course status is required.',
            'status.in' => 'Please select a valid course status.',
            
            'credit_units.required' => 'Credit units is required.',
            'credit_units.integer' => 'Credit units must be a number.',
            'credit_units.min' => 'Credit units must be at least 1.',
            'credit_units.max' => 'Credit units cannot exceed 6.',
            
            'image.image' => 'Course image must be an image file.',
            'image.mimes' => 'Course image must be a JPEG, PNG, JPG, or GIF file.',
            'image.max' => 'Course image size cannot exceed 5MB.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'title' => 'course title',
            'code' => 'course code',
            'description' => 'course description',
            'level' => 'course level',
            'semester' => 'semester',
            'status' => 'course status',
            'credit_units' => 'credit units',
            'image' => 'course image'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        // Convert code to uppercase for consistency
        if ($this->has('code')) {
            $this->merge([
                'code' => strtoupper($this->code)
            ]);
        }

        // Trim whitespace from title and description
        if ($this->has('title')) {
            $this->merge([
                'title' => trim($this->title)
            ]);
        }

        if ($this->has('description')) {
            $this->merge([
                'description' => trim($this->description)
            ]);
        }
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        // Log validation errors for debugging
        Log::warning('Course validation failed', [
            'errors' => $validator->errors()->toArray(),
            'input' => $this->except(['image']),
            'user_id' => auth()->id(),
            'route' => $this->route()->getName()
        ]);

        parent::failedValidation($validator);
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Additional custom validation logic can go here
            
            // Example: Check if the combination of level and semester makes sense
            if ($this->level && $this->semester) {
                // Add any business logic validation here
                // For example, certain courses might only be available in specific semesters
            }
        });
    }
}