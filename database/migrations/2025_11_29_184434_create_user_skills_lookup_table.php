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
        Schema::create('user_skills', function (Blueprint $table) {
    // 1. الأعمدة الأساسية
    $table->id();
    $table->unsignedBigInteger('user_id'); // يمثل المتطوع
    $table->unsignedBigInteger('skill_id'); // المعرف من جدول skills_lookup
    
    // 2. المفاتيح الأجنبية
    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    // ربط المهارة بجدول المهارات الصحيح
    $table->foreign('skill_id')->references('id')->on('skills_lookup')->onDelete('cascade');
    
    // 3. منع التكرار
    $table->unique(['user_id', 'skill_id']);
    
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_skills_lookup');
    }
};
