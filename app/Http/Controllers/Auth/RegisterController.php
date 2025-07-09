<?php

    namespace App\Http\Controllers\Auth;

    use App\Http\Controllers\Controller;
    use App\Http\Requests\Auth\RegisterRequest;
    use App\Http\Requests\Auth\VerifyOtpRequest;
    use App\Http\Requests\Auth\ResendOtpRequest;
    use App\Mail\Auth\OtpMail;
    use App\Models\PendingUser;
    use App\Models\User;
    use App\Models\Otp;
    use App\Traits\ApiResponse;
    use App\Http\Resources\UserResource;
    use Illuminate\Http\JsonResponse;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Mail;
    use Illuminate\Support\Facades\RateLimiter;
    use Carbon\Carbon;
    use Illuminate\Support\Facades\Log;

    class RegisterController extends Controller
    {
        use ApiResponse;
        /**
         * إنشاء وإرسال رمز OTP إلى البريد الإلكتروني
         *
         * @param PendingUser $pendingUser المستخدم المؤقت
         * @throws \Exception إذا فشل إنشاء OTP أو إرسال البريد
         */
        private function generateAndSendOtp(PendingUser $pendingUser): void
        {
            // إنشاء رمز OTP جديد
            $otp = random_int(100000, 999999);
            try {
                $otpRecord = Otp::create([
                    'user_id' => $pendingUser->id,
                    'otp' => $otp,
                    'expires_at' => Carbon::now()->addMinutes(3),
                ]);
                if (!$otpRecord) {
                    throw new \Exception('Failed to create OTP record');
                }
                Log::info('OTP created successfully for user_id: ' . $pendingUser->id);
            } catch (\Exception $e) {
                Log::error('Failed to create OTP for user_id: ' . $pendingUser->id . '. Error: ' . $e->getMessage());
                throw $e;
            }

            // إرسال OTP باستخدام Mailable
            try {
                Mail::to($pendingUser->email)->send(new OtpMail($pendingUser->name, $otp));
                Log::info('OTP email sent successfully to: ' . $pendingUser->email);
            } catch (\Exception $e) {
                Log::error('Failed to send OTP email to: ' . $pendingUser->email . '. Error: ' . $e->getMessage());
                throw $e;
            }
        }

        /**
         * تسجيل مستخدم جديد وإرسال OTP
         *
         * @param RegisterRequest $request بيانات التسجيل
         * @return JsonResponse رد النجاح أو الفشل
         */
        public function register(RegisterRequest $request): JsonResponse
        {
            DB::beginTransaction();
            try {
                // تخزين البيانات في جدول pending_users
                $pendingUser = PendingUser::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone_number' => $request->phone_number,
                    'password' => bcrypt($request->password),
                ]);

                // إنشاء وإرسال OTP
                $this->generateAndSendOtp($pendingUser);

                DB::commit();
                return $this->successResponse([
                    'email' => $pendingUser->email,
                    'otp_expires_at' => Carbon::now()->addMinutes(3),
                ], 'A verification code has been sent to your email.', 201);
            } catch (\Exception $e) {
                DB::rollBack();
                return $this->serverErrorResponse('An error occurred while registering.');
            }
        }

        /**
         * التحقق من رمز OTP ونقل البيانات إلى جدول users
         *
         * @param VerifyOtpRequest $request بيانات التحقق
         * @return JsonResponse رد النجاح أو الفشل
         */
        public function verifyOtp(VerifyOtpRequest $request): JsonResponse
        {
            // البحث عن المستخدم في جدول pending_users
            $pendingUser = PendingUser::where('email', $request->email)->first();

            if (!$pendingUser) {
                return $this->errorResponse('Invalid email address or user not found.', 400);
            }

            // البحث عن OTP في جدول otps
            $otpRecord = Otp::where('user_id', $pendingUser->id)
                ->where('otp', $request->otp)
                ->first();

            // التحقق من وجود OTP وصلاحيته
            if (!$otpRecord || Carbon::now()->greaterThan($otpRecord->expires_at)) {
                return $this->errorResponse('OTP code is invalid or expired.', 400);
            }

            // نقل البيانات إلى جدول users
            DB::beginTransaction();
            try {
                $user = User::create([
                    'name' => $pendingUser->name,
                    'email' => $pendingUser->email,
                    'phone_number' => $pendingUser->phone_number,
                    'password' => $pendingUser->password,
                ]);

                // حذف المستخدم من جدول pending_users
                $pendingUser->delete();

                // حذف OTP من جدول otps
                $otpRecord->delete();

                // إنشاء توكن باستخدام Sanctum
                $token = $user->createToken('auth_token')->plainTextToken;

                DB::commit();

                return $this->successResponse([
                    'user' => new UserResource($user),
                    'token' => $token
                ], 'OTP verified successfully.');
            } catch (\Exception $e) {
                DB::rollBack();
                return $this->serverErrorResponse('An error occurred while verifying.');
            }
        }

        /**
         * إعادة إرسال رمز OTP
         *
         * @param ResendOtpRequest $request بيانات إعادة الإرسال
         * @return JsonResponse رد النجاح أو الفشل
         */
        public function resendOtp(ResendOtpRequest $request): JsonResponse
        {
            // التحقق من الحد الأقصى لمحاولات إعادة الإرسال
            $key = 'resend-otp:' . $request->email;
            if (RateLimiter::tooManyAttempts($key, 3)) {
                $seconds = RateLimiter::availableIn($key);
                return $this->errorResponse(
                    "You have exceeded the maximum number of resend attempts. Please try again after $seconds seconds.",
                    429
                );
            }

            $pendingUser = PendingUser::where('email', $request->email)->first();

            // التحقق من وجود المستخدم
            if (!$pendingUser) {
                return $this->errorResponse('Invalid email address or user not found.', 400);
            }

            // التحقق مما إذا كان هناك OTP صالح
            $existingOtp = Otp::where('user_id', $pendingUser->id)
                ->where('expires_at', '>', Carbon::now())
                ->first();

            if ($existingOtp) {
                return $this->errorResponse('The current OTP is still valid. Please wait until it expires.', 400);
            }

            // تسجيل محاولة إعادة الإرسال
            RateLimiter::hit($key, 3600); // الحد: 3 محاولات لكل ساعة

            // إنشاء وإرسال OTP جديد
            try {
                $this->generateAndSendOtp($pendingUser);
                return $this->successResponse([
                    'email' => $pendingUser->email,
                    'otp_expires_at' => Carbon::now()->addMinutes(3),
                ], 'A new OTP has been sent to your email.');
            } catch (\Exception $e) {
                return $this->serverErrorResponse('An error occurred while sending OTP.');
            }
        }
    }
