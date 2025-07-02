<?php

  namespace App\Http\Requests\Auth;

  use Illuminate\Foundation\Http\FormRequest;

  class ResendResetOtpRequest extends FormRequest
  {
      public function authorize(): bool
      {
          return true;
      }

      public function rules(): array
      {
          return [
              'email' => ['required', 'email', 'exists:users,email'],
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
