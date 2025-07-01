<?php

namespace App\Http\Requests;

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
            'email.required' => 'البريد الإلكتروني مطلوب.',
            'email.email' => 'البريد الإلكتروني غير صالح.',
            'email.exists' => 'البريد الإلكتروني غير موجود في قاعدة البيانات.',
            'otp.required' => 'رمز OTP مطلوب.',
            'otp.size' => 'رمز OTP يجب أن يكون 6 أرقام.',
            'otp.exists' => 'رمز OTP غير صالح.',
        ];
    }
}
