<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ResendOtpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // السماح لجميع المستخدمين بالوصول
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email|exists:pending_users,email',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Email is required.',
            'email.email' => 'Email is invalid.',
            'email.exists' => 'Email is not found in the database.',
        ];
    }
}
