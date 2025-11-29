<?php

namespace App\Http\Controllers;

use App\Models\User; // سنحتاج لنموذج المستخدم
use App\Models\VolunteerProfile; // سنحتاج لإنشاء ملف المتطوع
use App\Models\CharityProfile; // سنحتاج لإنشاء ملف الجمعية
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // لتشفير كلمات المرور
use Illuminate\Support\Facades\Validator; // للتحقق من صحة البيانات المدخلة

    class AuthController extends Controller
    {
        
        public function register(Request $request)
{
     // 1. التحقق من صحة البيانات المدخلة (Validation) - تم إضافة city_id و phone
    $validator = Validator::make($request->all(), [l(), [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
        'user_type' => 'required|in:volunteer,charity',
        'city_id' => 'required|exists:cities,id', // ✨ تمت إضافة قاعدة التحقق من city_id
        'phone' => 'nullable|string|max:20', // ✨ إضافة حقل الهاتف (nullable مؤقتاً)
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }
    
    // ✨ 2. استخراج البيانات التي نجحت في التحقق وتعيينها للمتغير $validated
    $validated = $request->all();

    // 2. إنشاء سجل المستخدم الأساسي (User Record) - استخدام المتغير $validated
    $user = User::create([
        'name' => $validated['name'], // استخدام $validated
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']), // استخدام $validated
        'user_type' => $validated['user_type'], // استخدام $validated
        'is_active' => true,
    ]);

    // 3. إنشاء الملف الشخصي المرتبط (Profile Creation)
    if ($user->user_type === 'charity') {
        CharityProfile::create([
            'user_id' => $user->id,
            'name' => $validated['name'],
            'city_id' => $validated['city_id'], // ✨ تصحيح الخطأ: استبدال القيمة الثابتة (1) بالبيانات المدخلة
            'phone' => $validated['phone'] ?? null, // ✨ إضافة حقل الهاتف
            'name_ar' => $validated['name'],
        ]);
    } elseif ($user->user_type === 'volunteer') {
        // إنشاء ملف متطوع - استخدام المتغير $validated
        VolunteerProfile::create([
            'user_id' => $user->id,
            'name' => $validated['name'], // استخدام $validated
            'city_id' => $validated['city_id'], // استخدام $validated
            'phone' => $validated['phone'] ?? null, // ✨ إضافة حقل الهاتف
        ]);
    }
    
    // ... (بقية الدالة كما هي)
    
    // 4. إنشاء رمز المصادقة (Sanctum Token)
    $token = $user->createToken('AuthToken')->plainTextToken;

    // 5. إرجاع الاستجابة (Response)
    return response()->json([
        'message' => 'User registered successfully and profile created.',
        'user' => $user,
        'token' => $token,
    ], 201);
}
        
        public function login(Request $request)
        {
            // 1. التحقق من صحة البيانات
            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email|max:255',
                'password' => 'required|string|min:8',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            // 2. محاولة إيجاد المستخدم
            $user = User::where('email', $request->email)->first();

            // 3. التحقق من وجود المستخدم وصحة كلمة المرور
            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'message' => 'Invalid login details or user not found.'
                ], 401);
            }

            // 4. إنشاء رمز المصادقة للمستخدم
            $token = $user->createToken('AuthToken')->plainTextToken;

            // 5. إرجاع الاستجابة
            return response()->json([
                'message' => 'Logged in successfully.',
                'user' => $user,
                'token' => $token,
            ]);
        }

        public function logout(Request $request)
        {
            // استخدام currentAccessToken() لحذف الرمز الذي يستخدمه المستخدم حاليًا
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'message' => 'Successfully logged out.'
            ], 200);
        }
    }