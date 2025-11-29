<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // نستخدم Schema::table لتعديل الجدول الموجود. 
        // الأعمدة user_id، bio، city_id... إلخ لا تتأثر.
        Schema::table('volunteer_profile', function (Blueprint $table) {
            // 1. 🗑️ حذف الأعمدة القديمة التي نريد التخلص منها
            $table->dropColumn(['first_name', 'last_name']);
            
            // 2. ✨ إضافة العمود الجديد 'name' (حقل واحد للاسم)
            $table->string('name', 200)->after('city_id')->notNull(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('volunteer_profile', function (Blueprint $table) {
            // 1. 🗑️ حذف العمود الجديد (name)
            $table->dropColumn('name');
            
            // 2. 🔄 إعادة الأعمدة القديمة (first_name و last_name) إذا أردنا التراجع
            $table->string('first_name', 100)->after('city_id')->notNull();
            $table->string('last_name', 100)->after('first_name')->notNull();
        });
    }
};