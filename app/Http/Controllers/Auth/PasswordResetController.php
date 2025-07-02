<?php

    namespace App\Http\Controllers\Auth;

    use App\Http\Controllers\Controller;
    use App\Http\Requests\Auth\ForgotPasswordRequest;
    use App\Http\Requests\Auth\VerifyResetOtpRequest;
    use App\Http\Requests\Auth\ResetPasswordRequest;
    use App\Http\Requests\Auth\ResendResetOtpRequest;
    use App\Mail\Auth\PasswordResetMail;
    use App\Models\PasswordResetToken;
    use App\Models\User;
    use Carbon\Carbon;
    use Illuminate\Http\JsonResponse;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Log;
    use Illuminate\Support\Facades\Mail;
    use Illuminate\Support\Facades\RateLimiter;

    class PasswordResetController extends Controller
    {
        /**
         * إرسال OTP لإعادة تعيين كلمة المرور
         */
        public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
        {
            $key = 'forgot-password:' . $request->email . '|' . $request->ip();
            if (RateLimiter::tooManyAttempts($key, 3)) {
                $seconds = RateLimiter::availableIn($key);
                return response()->json([
                    'status' => 'error',
                    'message' => "You have exceeded the maximum number of OTP sending attempts. Try again in $seconds seconds."
                ], 429);
            }

            $user = User::where('email', $request->email)->first();

            $otp = random_int(100000, 999999);
            DB::beginTransaction();
            try {
                PasswordResetToken::updateOrCreate(
                    ['email' => $request->email],
                    [
                        'otp' => $otp,
                        'expires_at' => Carbon::now()->addMinutes(3),
                        'created_at' => Carbon::now(),
                    ]
                );

                Mail::to($request->email)->send(new PasswordResetMail($user->name, $otp));
                Log::info('Password reset OTP sent successfully to: ' . $request->email);

                RateLimiter::hit($key, 3600);

                DB::commit();
                return response()->json([
                    'status' => 'success',
                    'message' => 'OTP sent to your email.',
                    'data' => [
                        'email' => $request->email,
                        'otp_expires_at' => Carbon::now()->addMinutes(3),
                    ],
                ], 200);
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Failed to send OTP to: ' . $request->email . '. Error: ' . $e->getMessage());
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to send OTP.',
                    'error' => $e->getMessage(),
                ], 500);
            }
        }

          /**
     * إعادة إرسال رمز OTP لاستعادة كلمة المرور
     *
     * @param ResendResetOtpRequest $request بيانات إعادة الإرسال
     * @return JsonResponse رد النجاح أو الفشل
     */
    public function resendResetOtp(ResendResetOtpRequest $request): JsonResponse
    {
        // التحقق من الحد الأقصى لمحاولات إعادة الإرسال
        $key = 'resend-reset-otp:' . $request->email . '|' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            return response()->json([
                'status' => 'error',
                'message' => "You have exceeded the maximum number of OTP sending attempts. Try again in $seconds seconds."
            ], 429);
        }

        // التحقق من وجود البريد الإلكتروني في جدول المستخدمين
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'The provided email does not exist in our records.'
            ], 400);
        }

        // التحقق مما إذا كان هناك OTP صالح
        $existingToken = PasswordResetToken::where('email', $request->email)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if ($existingToken) {
            return response()->json([
                'status' => 'error',
                'message' => ' An active OTP already exists for this email. Please wait until it expires or use the existing OTP.'
            ], 400);
        }

        // تسجيل محاولة إعادة الإرسال
        RateLimiter::hit($key, 3600); // الحد: 3 محاولات لكل ساعة

        // إنشاء وإرسال OTP جديد
        DB::beginTransaction();
        try {
            $otp = random_int(100000, 999999);
            PasswordResetToken::updateOrCreate(
                ['email' => $request->email],
                [
                    'otp' => $otp,
                    'expires_at' => Carbon::now()->addMinutes(3),
                    'created_at' => Carbon::now(),
                ]
            );

            Mail::to($request->email)->send(new PasswordResetMail($user->name, $otp));
            Log::info('Password reset OTP resent successfully to: ' . $request->email);

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'OTP resent to your email.',
                'data' => [
                    'email' => $request->email,
                    'otp_expires_at' => Carbon::now()->addMinutes(3),
                ],
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to resend OTP to: ' . $request->email . '. Error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while sending the OTP.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


        /**
         * التحقق من OTP
         */
        public function verifyOtp(VerifyResetOtpRequest $request): JsonResponse
        {
            $key = 'verify-otp:' . $request->email . '|' . $request->ip();
            if (RateLimiter::tooManyAttempts($key, 5)) {
                $seconds = RateLimiter::availableIn($key);
                return response()->json([
                    'status' => 'error',
                    'message' => "You have exceeded the maximum number of OTP verification attempts. Try again in $seconds seconds."
                ], 429);
            }

            $resetToken = PasswordResetToken::where('email', $request->email)
                ->where('otp', $request->otp)
                ->first();

            if (!$resetToken || Carbon::now()->greaterThan($resetToken->expires_at)) {
                RateLimiter::hit($key, 4800);
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid or expired OTP.',
                ], 400);
            }

            RateLimiter::clear($key);
            Log::info('OTP verified successfully for: ' . $request->email);

            return response()->json([
                'status' => 'success',
                'message' => 'OTP verified successfully. You can now reset your password.',
            ], 200);
        }

        /**
         * إعادة تعيين كلمة المرور
         */
        public function resetPassword(ResetPasswordRequest $request): JsonResponse
        {
            $key = 'reset-password:' . $request->email . '|' . $request->ip();
            if (RateLimiter::tooManyAttempts($key, 5)) {
                $seconds = RateLimiter::availableIn($key);
                return response()->json([
                    'status' => 'error',
                    'message' => "You have exceeded the maximum number of password reset attempts. Try again in $seconds seconds."
                ], 429);
            }

            $resetToken = PasswordResetToken::where('email', $request->email)
                ->where('otp', $request->otp)
                ->first();

            if (!$resetToken || Carbon::now()->greaterThan($resetToken->expires_at)) {
                RateLimiter::hit($key, 3600);
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid or expired OTP.',
                ], 400);
            }

            DB::beginTransaction();
            try {
                $user = User::where('email', $request->email)->first();
                /** @var \App\Models\User $user */
                $user->update([
                    'password' => bcrypt($request->password),
                ]);

                $token = $user->createToken('auth_token')->plainTextToken;

                $resetToken->delete();

                RateLimiter::clear($key);
                Log::info('Password reset successfully for user: ' . $user->email);

                DB::commit();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Password reset successfully.',
                    'data' => [
                        'user' => $user,
                        'token' => $token,
                    ],
                ], 200);
            } catch (\Exception $e) {
                DB::rollBack();
                RateLimiter::hit($key, 3600);
                Log::error('Failed to reset password for: ' . $request->email . '. Error: ' . $e->getMessage());
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to reset password.',
                    'error' => $e->getMessage(),
                ], 500);
            }
        }
    }
