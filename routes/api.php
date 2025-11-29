<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\CharityProfileController;
use App\Http\Controllers\UserController; // ✨ إضافة UserController

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// مسارات المصادقة العامة (Public Routes)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// مسار جلب جميع المدن (عام، لا يتطلب مصادقة)
Route::get('/cities', [CityController::class, 'index']);

// مسارات المشاريع العامة (Public Project Routes)
// يمكن لأي شخص عرض المشاريع المتاحة وتفاصيل مشروع واحد
Route::get('/projects', [ProjectController::class, 'index']); 
Route::get('/projects/{project}', [ProjectController::class, 'show']); 

// مسارات المصادقة المحمية (Protected Routes)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // ✨ 1. مسار جلب المستخدم الحالي مع ملفه الشخصي وعلاقاته
    Route::get('/users/me', [UserController::class, 'me']); 
    
    // 2. تحديث ملف الجمعية الشخصي
    Route::put('/charity/profile', [CharityProfileController::class, 'update']);

    // 3. مسارات إدارة المشاريع (إنشاء، تحديث، حذف، إضافة مهارات)
    Route::post('/projects', [ProjectController::class, 'store']); // إنشاء مشروع
    Route::put('/projects/{project}', [ProjectController::class, 'update']); // تحديث مشروع
    Route::delete('/projects/{project}', [ProjectController::class, 'destroy']); // حذف مشروع
    Route::post('projects/{project}/skills', [ProjectController::class, 'syncSkills']); // مزامنة المهارات

    // 4. مسار تقديم المتطوعين على المشاريع
    Route::post('/projects/{projectId}/apply', [ProjectController::class, 'apply']);
    
    // ملاحظة: مسار 'projects' تم تقسيمه إلى مسارات POST/PUT/DELETE فردية لتنظيم أفضل
});