<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * جلب بيانات المستخدم الحالي مع تحميل ملفه الشخصي (Profile) بناءً على نوعه.
     */
    public function me(Request $request)
    {
        $user = Auth::user();

        // 1. تحديد العلاقة المراد تحميلها بناءً على user_type
        if ($user->user_type === 'charity') {
            // تحميل ملف الجمعية والمدينة المرتبطة به (للتأكد من صحة العلاقتين)
            $user->load('charityProfile.city'); 
        } elseif ($user->user_type === 'volunteer') {
            // تحميل ملف المتطوع والمدينة المرتبطة به
            $user->load('volunteerProfile.city'); 
        }

        // 2. إرجاع بيانات المستخدم المحملة
        return response()->json([
            'message' => 'User data retrieved successfully.',
            'user' => $user,
        ], 200);
    }
}