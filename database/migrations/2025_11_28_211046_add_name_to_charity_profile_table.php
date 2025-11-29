<?php

use Illuminate\Database\Migrations\Migration; // يجب استيراد هذا الكلاس
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // تم تغيير 'charity_profile' إلى 'charities_profile'
        Schema::table('charities_profile', function (Blueprint $table) {
            $table->string('name', 200)->after('user_id')->notNull();
        });
    }

    public function down(): void
    {
        // تم تغيير 'charity_profile' إلى 'charities_profile'
        Schema::table('charities_profile', function (Blueprint $table) {
            $table->dropColumn('name');
        });
    }
};