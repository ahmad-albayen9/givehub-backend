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
       Schema::create('volunteer_project_skills', function (Blueprint $table) {
       $table->id(); 
       $table->foreignId('project_id')->constrained('projects')->onDelete('cascade'); 
       $table->foreignId('volunteer_id')->constrained('volunteer_profile', 'user_id')->onDelete('cascade'); 
       $table->foreignId('skill_id')->constrained('skills_lookup')->onDelete('restrict'); 
       $table->integer('rating_value')->unsigned()->check('rating_value >= 1 AND rating_value <= 5'); 
       $table->foreignId('charity_id')->constrained('charities_profile', 'user_id')->onDelete('restrict'); 
       $table->timestamps(); 
       $table->unique(['project_id', 'volunteer_id', 'skill_id']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('volunteer_project_skills');
    }
};
