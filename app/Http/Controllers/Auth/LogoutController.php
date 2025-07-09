<?php

    namespace App\Http\Controllers\Auth;

    use App\Http\Controllers\Controller;
    use App\Traits\ApiResponse;
    use Illuminate\Http\JsonResponse;
    use Illuminate\Support\Facades\Auth;

    class LogoutController extends Controller
    {
        use ApiResponse;
        /**
         * تنفيذ عملية تسجيل الخروج
         *
         * @return JsonResponse رد يؤكد نجاح تسجيل الخروج
         */
        public function logout(): JsonResponse
        {
            // الحصول على المستخدم المصادق عليه
            $user = Auth::guard('sanctum')->user();

            if ($user) {
                // حذف جميع التوكنات النشطة للمستخدم
                /** @var \App\Models\User $user */
                $user->tokens()->delete();

                return $this->successMessage('Successfully logged out.');
            }

            return $this->errorResponse('No authenticated user found.', 401);
        }
    }
