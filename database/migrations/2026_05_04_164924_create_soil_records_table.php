<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('soil_records', function (Blueprint $table) {
        $table->id();
        $table->foreignId('plot_id')->constrained()->onDelete('cascade'); // ربط السجل بالأرض
        $table->float('ph_level');        // مستوى الحموضة
        $table->string('fertilizer_type'); // نوع السماد
        $table->string('crop_type');      // نوع الزرع
        $table->text('notes')->nullable(); // ملاحظات المستخدم
        $table->date('record_date');      // تاريخ تسجيل الحالة
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('soil_records');
    }
};
