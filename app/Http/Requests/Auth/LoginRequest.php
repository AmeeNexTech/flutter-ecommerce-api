<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * تحديد ما إذا كان المستخدم مخولًا لتقديم هذا الطلب
     */
    public function authorize(): bool
    {
        return true; // السماح للجميع بتقديم طلب تسجيل الدخول
    }

    /**
     * قواعد التحقق من بيانات الطلب
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'exists:users,email'], // التحقق من وجود البريد في جدول users
            'password' => ['required', 'string', 'min:8'], // كلمة المرور مطلوبة وبطول 8 أحرف على الأقل
        ];
    }

    /**
     * رسائل الخطأ المخصصة
     */
    public function messages(): array
    {
        return [
            'email.required' => 'Email address is required.',
            'email.email' => 'Email address is invalid.',
            'email.exists' => 'Email address is not registered.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters.',
        ];
    }
}
