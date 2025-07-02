<?php

     namespace App\Http\Requests\Auth;

     use Illuminate\Foundation\Http\FormRequest;

     class ResetPasswordRequest extends FormRequest
     {
         public function authorize(): bool
         {
             return true; // السماح للجميع بتقديم طلب إعادة تعيين كلمة المرور
         }

         public function rules(): array
         {
             return [
                 'email' => ['required', 'email', 'exists:users,email'],
                 'otp' => ['required', 'string', 'size:6'], // OTP مكون من 6 أرقام
                 'password' => ['required', 'string', 'min:8', 'confirmed'],
             ];
         }

         public function messages(): array
         {
             return [
                 'email.required' => 'Email is required.',
                 'email.email' => 'Email is invalid.',
                 'email.exists' => 'The provided email does not exist in our records.',
                 'otp.required' => 'OTP is required.',
                 'otp.size' => 'OTP must be 6 digits.',
                 'password.required' => 'Password is required.',
                 'password.min' => 'Password must be at least 8 characters.',
                 'password.confirmed' => 'Password confirmation does not match.',
             ];
         }
     }
