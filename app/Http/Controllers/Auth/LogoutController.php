<?php

    namespace App\Http\Controllers\Auth;

    use App\Http\Controllers\Controller;
    use Illuminate\Http\JsonResponse;
    use Illuminate\Support\Facades\Auth;

    class LogoutController extends Controller
    {
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

                return response()->json([
                    'status' => 'success',
                    'message' => 'تم تسجيل الخروج بنجاح.',
                ], 200);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'لا يوجد مستخدم مصادق عليه.',
            ], 401);
        }
    }
