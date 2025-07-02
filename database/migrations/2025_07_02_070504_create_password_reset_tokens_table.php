<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        /**
         * تشغيل الميغريشن
         */
        public function up(): void
        {
            Schema::create('password_reset_tokens', function (Blueprint $table) {
                $table->string('email')->primary(); // البريد الإلكتروني كمفتاح أساسي
                $table->string('otp'); // رمز OTP (بدلاً من token)
                $table->timestamp('expires_at'); // تاريخ انتهاء الصلاحية
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->nullable();

            });
        }

        /**
         * إلغاء الميغريشن
         */
        public function down(): void
        {
            Schema::dropIfExists('password_reset_tokens');
        }
    };
