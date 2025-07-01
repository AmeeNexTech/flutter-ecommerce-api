<?php

namespace App\Http\Requests;

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
            'email.required' => 'البريد الإلكتروني مطلوب.',
            'email.email' => 'البريد الإلكتروني غير صالح.',
            'email.exists' => 'البريد الإلكتروني غير موجود في قاعدة البيانات.',
        ];
    }
}
