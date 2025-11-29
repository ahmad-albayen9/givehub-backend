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
        Schema::table('projects', function (Blueprint $table) {
        // ✨ إضافة العمود الجديد والربط بجدول المستخدمين
        $table->foreignId('leader_user_id')->nullable()->after('charity_id')->constrained('users')->onDelete('set null');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::table('projects', function (Blueprint $table) {
        // حذف مفتاح الربط أولاً ثم حذف العمود
        $table->dropForeign(['leader_user_id']);
        $table->dropColumn('leader_user_id');
    });
    }
};
