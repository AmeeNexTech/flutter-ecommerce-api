<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;

    class PasswordResetToken extends Model
    {
        protected $primaryKey = 'email'; // المفتاح الأساسي هو البريد الإلكتروني
        public $incrementing = false; // غير متزايد (لأن المفتاح سلسلة)
        protected $keyType = 'string'; // نوع المفتاح سلسلة

        protected $fillable = [
            'email',
            'otp',
            'expires_at',
            'created_at',
        ];

        protected $casts = [
            'expires_at' => 'datetime',
            'created_at' => 'datetime',
        ];
    }
