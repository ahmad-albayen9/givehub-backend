<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // استيراد DB

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. تعطيل فحص القيود الأجنبية (مهم لضمان نجاح migrate:fresh)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // 2. استدعاء ملفات التغذية
        $this->call([
            CitySeeder::class, 
            SkillsLookupSeeder::class,
        ]);

        $defaultPasswordHash = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'; // كلمة المرور: 'password'

        // 3. إنشاء مستخدم متطوع
        User::firstOrCreate([
            'email' => 'volunteer@test.com',
        ], [
            'name' => 'Volunteer Tester',
            'password' => $defaultPasswordHash,
            'user_type' => 'volunteer', 
            'is_active' => true,
        ]);

        // 4. إنشاء مستخدم جمعية
        User::firstOrCreate([
            'email' => 'charity@test.com',
            'user_type' => 'charity', 
        ], [
            'name' => 'Charity Manager',
            'password' => $defaultPasswordHash,
            'is_active' => true,
        ]);

        // 5. إنشاء مستخدم مسؤول (Admin)
        User::firstOrCreate([
            'email' => 'admin@test.com',
            'user_type' => 'admin', 
        ], [
            'name' => 'Admin User',
            'password' => $defaultPasswordHash,
            'is_active' => true,
        ]);
        
        // 6. إعادة تفعيل فحص القيود الأجنبية
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}