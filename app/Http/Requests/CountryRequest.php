<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CountryRequest extends FormRequest
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
        return [
            'country_code' => 'required|max:2',
            'country_name' => 'required'
        ];
    }

    public function messages() {
        return [
            'required' => 'Trường :attribute không được để trống',
            'max' => 'Trường :attribute không quá :max kí tự',
        ];
    }

    public function attributes() {
        return [
            'country_code' => 'country code',
            'country_name' => 'country name'
        ];
    }
}
