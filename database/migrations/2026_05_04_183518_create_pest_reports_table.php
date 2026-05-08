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
    Schema::create('pest_reports', function (Blueprint $table) {
        $table->id();
        $table->foreignId('plot_id')->constrained()->onDelete('cascade');
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->string('pest_type'); // نوع الحشرة أو الآفة
        $table->text('description'); // وصف المشكلة
        $table->enum('status', ['pending', 'resolved'])->default('pending'); // حالة البلاغ
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pest_reports');
    }
};
