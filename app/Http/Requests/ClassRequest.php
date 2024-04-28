<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClassRequest extends FormRequest
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
            'code' => 'required|max:6',
            'name' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'course_id' => 'required',
            'schedule' => 'required',
            'description' => 'required',
        ];

        if ($this->isMethod('PUT')) {
            $rules['code'] .= '|unique:classes,code,' . $this->route('id');
        } else {
            $rules['code'] .= '|unique:classes,code';
        }

        return $rules;
    }
}
