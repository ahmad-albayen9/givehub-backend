<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateCharityProfileRequest; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CharityProfileController extends Controller
{
    // ... يمكن إضافة دالة index أو show هنا لاحقًا

    public function update(UpdateCharityProfileRequest $request)
    {
        // 1. التأكد من أن المستخدم الحالي هو جمعية (للتحقق من user_type)
        if (Auth::user()->user_type !== 'charity') {
            return response()->json([
                'message' => 'Unauthorized. Only charities can update their profile.'
            ], 403);
        }
        
        // 2. الوصول إلى ملف الجمعية المرتبط بالمستخدم الحالي (عبر علاقة charityProfile)
        $charityProfile = Auth::user()->charityProfile;

        if (!$charityProfile) {
            return response()->json([
                'message' => 'Charity profile not found for this user.'
            ], 404);
        }

        // 3. تحديث البيانات باستخدام البيانات المتحقق منها (Validated Data)
        $charityProfile->update($request->validated());
        
        // 4. إرجاع الاستجابة
        return response()->json([
            'message' => 'Charity profile updated successfully.',
            // تحميل المدينة لعرضها في الاستجابة
            'profile' => $charityProfile->load('city') 
        ], 200);
    }
}