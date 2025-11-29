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
       Schema::create('volunteer_profile', function (Blueprint $table) {
        $table->foreignId('user_id')->primary()->constrained('users')->onDelete('cascade'); 
        $table->string('first_name', 100)->notNull(); 
        $table->string('last_name', 100)->notNull(); 
        $table->text('bio')->nullable(); 
        $table->foreignId('city_id')->nullable()->constrained('cities')->onDelete('set null'); 
        $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('volunteer_profile');
    }
};
