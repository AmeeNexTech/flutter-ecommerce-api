<?php

  namespace App\Http\Controllers\User;

  use App\Http\Controllers\Controller;
  use App\Http\Requests\User\DeleteAccountRequest;
  use App\Traits\ApiResponse;
  use Illuminate\Http\JsonResponse;
  use Illuminate\Support\Facades\Auth;
  use Illuminate\Support\Facades\Hash;
  use Illuminate\Support\Facades\DB;

  class UserController extends Controller
  {
      use ApiResponse;
      /**
       * حذف حساب المستخدم
       *
       * @param DeleteAccountRequest $request الطلب الذي يحتوي على كلمة المرور
       * @return JsonResponse رد يؤكد نجاح الحذف أو الفشل
       */
      public function deleteAccount(DeleteAccountRequest $request): JsonResponse
      {
          // الحصول على المستخدم المصادق عليه باستخدام Sanctum
          $user = Auth::guard('sanctum')->user();

          // التحقق من وجود مستخدم مصادق عليه
          if (!$user) {
              return $this->errorResponse('No authenticated user found.', 401);
          }

          // التحقق من كلمة المرور
          if (!Hash::check($request->input('password'), (string) $user->password)) {
              return $this->errorResponse('Invalid password.', 422);
          }

          // بدء معاملة قاعدة البيانات لضمان السلامة
          DB::beginTransaction();
          try {
              // حذف جميع التوكنات المرتبطة بالمستخدم
               /** @var \App\Models\User $user */
              $user->tokens()->delete();

              // حذف الحساب من قاعدة البيانات
              $user->delete();

              // تأكيد المعاملة
              DB::commit();

              return $this->successMessage('Account deleted successfully.');
          } catch (\Exception $e) {
              // التراجع في حالة الخطأ
              DB::rollBack();

              return $this->serverErrorResponse('Failed to delete account.');
          }
      }
  }
