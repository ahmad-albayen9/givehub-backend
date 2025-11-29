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
        Schema::create('projects', function (Blueprint $table) {
    $table->id(); 
    
    // ربط المستخدم (الجمعية) مباشرة
    $table->foreignId('charity_id')->constrained('charities_profile', 'user_id')->onDelete('cascade'); 
    
    // ✨ التعديل هنا: تعريف العمود فقط (مفتاح أجنبي)
    $table->unsignedBigInteger('team_id')->nullable(); 
    
    $table->string('title', 255)->notNull(); 
    $table->text('description')->notNull(); 
    $table->date('start_date'); 
    $table->integer('required_volunteers')->nullable(); 
    $table->foreignId('city_id')->constrained('cities')->onDelete('restrict'); 
    $table->enum('status', ['open', 'in_progress', 'completed', 'cancelled'])->default('open'); 
    $table->timestamps();
    
    // ✨ ملاحظة: لا تضع قيد المفتاح الأجنبي team_id هنا
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
