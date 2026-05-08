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
    Schema::create('complaints', function (Blueprint $table) {
        $table->id();
        $table->foreignId('plot_id')->constrained()->onDelete('cascade'); // مربوط بالأرض
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); // مين المزارع اللي اشتكى
        $table->string('title'); // عنوان الشكوى
        $table->text('description'); // تفاصيل المشكلة
        $table->string('status')->default('pending'); // حالة الشكوى (pending, resolved)
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
