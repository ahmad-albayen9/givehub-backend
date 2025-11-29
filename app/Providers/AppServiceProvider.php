<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route; // <--- هذا هو السطر المهم الذي يجب إضافته

class AppServiceProvider extends ServiceProvider
{
    // ... دالة register
    
    public function boot(): void
    {
     Route::middleware('api') // <--- هذه هي الطريقة الصحيحة إذا لم تكن دالة routes متاحة
            ->prefix('api')
            ->group(base_path('routes/api.php'));

        Route::middleware('web')
            ->group(base_path('routes/web.php'));   // يجب أن تكون الدالة فارغة بهذا الشكل، أو تحتوي على تعليق //
    }
}