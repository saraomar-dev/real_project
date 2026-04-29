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
    Schema::create('audit_logs', function (Blueprint $table) {
        $table->id();

        // مين عمل العملية
        $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

        // نوع العملية (create / update / delete)
        $table->string('action');

        // اسم الموديل (User / Post ...)
        $table->string('model');

        // id الخاص بالـ record
        $table->unsignedBigInteger('model_id')->nullable();

        // قبل وبعد التعديل
        $table->json('old_values')->nullable();
        $table->json('new_values')->nullable();

        // ip
        $table->string('ip')->nullable();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
