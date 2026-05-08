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
    Schema::table('plots', function (Blueprint $table) {
        // إضافة عمود الـ seed_id كخارجي (Foreign Key)
        $table->foreignId('seed_id')->nullable()->constrained('seeds')->onDelete('set null');
        
        // إضافة عمود تاريخ الزراعة
        $table->timestamp('planting_date')->nullable();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plots', function (Blueprint $table) {
            //
        });
    }
};
