<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() && $this->user()->role === 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'username' => [
                'required',
                'string',
                'min:3',
                'max:50',
                'unique:users,username',
                'regex:/^[a-zA-Z0-9_]+$/'
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email'
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/'
            ],
            'role' => [
                'required',
                'in:admin,staff,borrower'
            ]
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'username.required' => 'Username is required.',
            'username.unique' => 'This username is already taken.',
            'username.regex' => 'Username can only contain letters, numbers, and underscores.',
            'username.min' => 'Username must be at least 3 characters.',
            'username.max' => 'Username cannot exceed 50 characters.',
            'email.required' => 'Email is required.',
            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'This email is already registered.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, and one number.',
            'role.required' => 'User role is required.',
            'role.in' => 'Invalid user role selected.'
        ];
    }
}
