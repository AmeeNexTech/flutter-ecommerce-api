<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // تعيين طول الـ string الافتراضي لقاعدة البيانات
        Schema::defaultStringLength(191);

        // تسجيل الاستعلامات البطيئة في بيئة التطوير
        if (config('app.debug')) {
            DB::listen(function ($query) {
                if ($query->time > 100) { // استعلامات تستغرق أكثر من 100ms
                    Log::warning('Slow query detected', [
                        'sql' => $query->sql,
                        'bindings' => $query->bindings,
                        'time' => $query->time,
                    ]);
                }
            });
        }

        // تحسين إعدادات الكاش
        if (config('api.cache.enabled')) {
            // إعدادات إضافية للكاش
        }
    }
}
