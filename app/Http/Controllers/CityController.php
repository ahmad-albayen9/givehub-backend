<?php

namespace App\Http\Controllers;

use App\Models\City; // استدعاء نموذج المدينة لنتعامل مع قاعدة البيانات
use Illuminate\Http\Request;

class CityController extends Controller
{
    /**
     * جلب جميع المدن. هذا المسار يجب أن يكون عاماً (غير محمي) ليتسنى استخدامه في صفحات التسجيل.
     * * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            // جلب جميع السجلات من جدول المدن (City)
            $cities = City::all(); 

            // إرجاعها كاستجابة JSON بحالة نجاح 200
            return response()->json([
                'status' => 'success',
                'cities' => $cities,
            ], 200);

        } catch (\Exception $e) {
            // التعامل مع أي خطأ في الخادم أو قاعدة البيانات
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch cities: ' . $e->getMessage()
            ], 500);
        }
    }
}