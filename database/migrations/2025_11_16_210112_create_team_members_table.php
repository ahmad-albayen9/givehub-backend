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
        Schema::create('team_members', function (Blueprint $table) {
        $table->id(); 
        $table->foreignId('team_id')->constrained('teams')->onDelete('cascade'); 
        $table->foreignId('volunteer_id')->constrained('volunteer_profile', 'user_id')->onDelete('cascade'); 
        $table->timestamp('joined_at')->useCurrent(); 
        $table->timestamps(); 
        $table->unique(['team_id', 'volunteer_id']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_members');
    }
};
