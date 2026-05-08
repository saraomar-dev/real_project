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
       Schema::create('leases', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained(); // المستأجر
    $table->foreignId('plot_id')->constrained(); // قطعة الأرض
    $table->date('start_date'); // بداية الإيجار
    $table->date('end_date');   // نهاية الإيجار
    $table->enum('status', ['active', 'expired', 'terminated'])->default('active'); // حالة الإيجار
    $table->timestamps();
});

        
    }

    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leases');
    }
};
