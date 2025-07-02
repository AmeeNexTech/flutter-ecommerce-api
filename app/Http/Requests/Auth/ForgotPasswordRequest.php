<?php

     namespace App\Http\Requests\Auth;

     use Illuminate\Foundation\Http\FormRequest;

     class ForgotPasswordRequest extends FormRequest
     {
         public function authorize(): bool
         {
             return true; // السماح للجميع بتقديم طلب استعادة كلمة المرور
         }

         public function rules(): array
         {
             return [
                 'email' => ['required', 'email', 'exists:users,email'], // التحقق من وجود البريد
             ];
         }

         public function messages(): array
         {
             return [
                 'email.required' => 'Email is required.',
                 'email.email' => 'Email is invalid.',
                 'email.exists' => 'The provided email does not exist in our records.',
             ];
         }
     }
