<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // turn it to true to allow to validate user requests
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
            'name' => 'required|string|min:5|max:150',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|max:255',
            'phone_number' => 'required|digits:10|',
        ];
    }

    /**
     * Get the validation errors messages that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function messages(): array {
        return [
            // Here you can write a custom message for each rule
            'name.required' => 'Please enter a "name" and must be a string.',
            'name.min' => 'Name must be at least 5 characters long.',
        ];
    }
}
