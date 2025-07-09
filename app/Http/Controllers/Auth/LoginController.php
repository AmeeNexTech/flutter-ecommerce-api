<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Traits\ApiResponse;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;


class LoginController extends Controller
{
    use ApiResponse;

    /**
     * تسجيل دخول المستخدم باستخدام البريد الإلكتروني وكلمة المرور
     *
     * @param LoginRequest $request بيانات الطلب
     * @return JsonResponse رد النجاح أو الفشل
     */
    public function login(LoginRequest $request): JsonResponse
    {
        // التحقق من الحد الأقصى لمحاولات تسجيل الدخول
        $key = 'login:' . $request->email;
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return $this->errorResponse(
                "You have exceeded the maximum number of login attempts. Please try again after $seconds seconds.",
                429
            );
        }

        // التحقق من بيانات المستخدم
        if (!Auth::attempt($request->only('email', 'password'))) {
            RateLimiter::hit($key, 3600); // تسجيل محاولة فاشلة
            return $this->errorResponse('Invalid login credentials.', 401);
        }

        // الحصول على المستخدم
        $user = Auth::user();

   /** @var \App\Models\User $user */
   $token = $user->createToken('auth_token')->plainTextToken;

        // تسجيل محاولة ناجحة وإعادة تعيين العداد
        RateLimiter::clear($key);

        return $this->successResponse([
            'user' => new UserResource($user),
            'token' => $token
        ], 'Successfully logged in.');
    }
}
