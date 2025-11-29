<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SkillsLookupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // استخدام DB::table() لإدراج البيانات في جدول skills_lookup
        DB::table('skills_lookup')->insertOrIgnore([
            ['id' => 1, 'name' => 'التصوير والمونتاج', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'التسويق الإلكتروني', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'التصميم الجرافيكي', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'name' => 'إدخال البيانات', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}