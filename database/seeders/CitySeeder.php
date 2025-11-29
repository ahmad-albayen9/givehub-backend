<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
// لا نحتاج لاستدعاء Schema هنا بعد الآن

class CitySeeder extends Seeder
{
    /**
     * تشغيل ملف التغذية (Seeder)
     */
    public function run(): void
    {
        // 1. إيقاف قيود المفاتيح الخارجية باستخدام أمر SQL مباشر
        DB::statement('SET FOREIGN_KEY_CHECKS = 0'); 

        // 2. حذف جميع السجلات القديمة من جدول المدن
        DB::table('cities')->truncate(); 

        // 3. قائمة المدن (ملاحظة: تأكد من أن هذه هي القائمة الكاملة التي تريدها)
        $cities = [
            [
                'name_ar' => 'الرياض', 
                'name_en' => 'Riyadh', 
                'country_code' => 'SA', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'name_ar' => 'جدة', 
                'name_en' => 'Jeddah', 
                'country_code' => 'SA', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'name_ar' => 'الدمام', 
                'name_en' => 'Dammam', 
                'country_code' => 'SA', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            // يمكنك إضافة بقية المدن هنا...
        ];

        DB::table('cities')->insert($cities);

        // 4. إعادة تشغيل قيود المفاتيح الخارجية
        DB::statement('SET FOREIGN_KEY_CHECKS = 1'); 
    }
}