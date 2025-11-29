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
       Schema::create('volunteer_skills', function (Blueprint $table) {
       $table->id(); 
       $table->foreignId('volunteer_id')->constrained('volunteer_profile', 'user_id')->onDelete('cascade'); 
       $table->foreignId('skill_id')->constrained('skills_lookup')->onDelete('cascade'); 
       $table->integer('proficiency_level')->unsigned()->default(1); 
       $table->timestamps(); 
       $table->unique(['volunteer_id', 'skill_id']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('volunteer_skills');
    }
};
