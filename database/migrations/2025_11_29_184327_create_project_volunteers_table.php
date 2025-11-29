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
        Schema::create('project_volunteers', function (Blueprint $table) {
    // 1. الأعمدة الأساسية
    $table->id();
    $table->unsignedBigInteger('project_id');
    $table->unsignedBigInteger('user_id'); // يمثل المتطوع
    $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');
    
    // 2. المفاتيح الأجنبية
    $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    
    // 3. منع التكرار
    $table->unique(['project_id', 'user_id']);
    
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_volunteers');
    }
};
