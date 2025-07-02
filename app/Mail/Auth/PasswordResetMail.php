<?php

    namespace App\Mail\Auth;

    use Illuminate\Bus\Queueable;
    use Illuminate\Mail\Mailable;
    use Illuminate\Queue\SerializesModels;

    class PasswordResetMail extends Mailable
    {
        use Queueable, SerializesModels;

        public $name;
        public $otp;

        /**
         * إنشاء مثيل جديد للبريد
         *
         * @param string $name اسم المستخدم
         * @param string $otp رمز التحقق
         */
        public function __construct(string $name, string $otp)
        {
            $this->name = $name;
            $this->otp = $otp;
        }

        /**
         * بناء الرسالة
         *
         * @return $this
         */
        public function build()
        {
            return $this->subject('Password Reset OTP for Shoporia')
                        ->markdown('emails.password_reset_email', [
                            'name' => $this->name,
                            'otp' => $this->otp,
                        ]);
        }
    }
