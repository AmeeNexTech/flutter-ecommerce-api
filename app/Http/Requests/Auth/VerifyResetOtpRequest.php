<?php

  namespace App\Http\Requests\Auth;

  use Illuminate\Foundation\Http\FormRequest;

  class VerifyResetOtpRequest extends FormRequest
  {
      public function authorize(): bool
      {
          return true;
      }

      public function rules(): array
      {
          return [
              'email' => ['required', 'email', 'exists:users,email'],
              'otp' => ['required', 'string', 'size:6'],
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
          ];
      }
  }
