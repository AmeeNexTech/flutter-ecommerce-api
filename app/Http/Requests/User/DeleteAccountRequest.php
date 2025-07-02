<?php

  namespace App\Http\Requests\User;

  use Illuminate\Foundation\Http\FormRequest;

  class DeleteAccountRequest extends FormRequest
  {
      public function authorize(): bool
      {
          return true; // المصادقة تتم في الـ Controller باستخدام Sanctum
      }

      public function rules(): array
      {
          return [
              'password' => 'required|string',
          ];
      }

      public function messages(): array
      {
          return [
              'password.required' => 'كلمة المرور مطلوبة.',
              'password.string' => 'كلمة المرور يجب أن تكون نصًا.',
          ];
      }
  }
