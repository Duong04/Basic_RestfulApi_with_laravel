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
        $rules = [
            'course_code' => 'required|max:3',
            'course_name' => 'required',
            'course_image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ];

        if ($this->isMethod('PUT')) {
            $rules['course_code'] .= '|unique:courses,course_code,' .$this->route('id');
        }else {
            $rules['course_code'] .= '|unique:courses,course_code';
        }

        return $rules;
    }
}
