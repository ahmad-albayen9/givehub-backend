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
           Schema::create('teams', function (Blueprint $table) {
    $table->id(); 
    
    // ✨ التعديل هنا: تعريف العمود فقط (مفتاح أجنبي)
    $table->unsignedBigInteger('project_id')->nullable();
    
    $table->foreignId('leader_user_id')->nullable()->constrained('users')->onDelete('set null'); 
    $table->string('name', 255)->notNull(); 
    $table->integer('max_members'); 
    $table->timestamps();
    
    // ✨ ملاحظة: لا تضع قيد المفتاح الأجنبي project_id هنا
});
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('teams');
        }
    };
