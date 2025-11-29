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
       Schema::create('charities_profile', function (Blueprint $table) {
        $table->foreignId('user_id')->primary()->constrained('users')->onDelete('cascade'); 
        $table->string('name_ar', 255)->notNull(); 
        $table->string('license_number', 100)->unique()->nullable(); 
        $table->text('description')->nullable(); 
        $table->string('contact_person', 255)->nullable(); 
        $table->foreignId('city_id')->nullable()->constrained('cities')->onDelete('set null'); 
        $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('charities_profile');
    }
};
