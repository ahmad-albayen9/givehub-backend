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
       Schema::create('project_applications', function (Blueprint $table) {
       $table->id(); 
       $table->foreignId('project_id')->constrained('projects')->onDelete('cascade'); 
       $table->foreignId('volunteer_id')->constrained('volunteer_profile', 'user_id')->onDelete('cascade'); 
       $table->foreignId('team_id')->nullable()->constrained('teams')->onDelete('set null'); 
       $table->enum('status', ['Pending', 'Approved', 'Rejected', 'Withdrawn'])->default('Pending'); 
       $table->foreignId('reviewer_user_id')->nullable()->constrained('users')->onDelete('set null'); 
       $table->timestamps(); 
       $table->unique(['project_id', 'volunteer_id']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_applications');
    }
};
