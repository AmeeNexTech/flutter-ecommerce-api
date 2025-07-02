<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class VerifyOtpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email|exists:pending_users,email',
            'otp' => 'required|string|size:6|exists:otps,otp',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Email is required.',
            'email.email' => 'Email is invalid.',
            'email.exists' => 'Email is not found in the database.',
            'otp.required' => 'OTP is required.',
            'otp.size' => 'OTP must be 6 digits.',
            'otp.exists' => 'OTP is invalid.',
        ];
    }
}
