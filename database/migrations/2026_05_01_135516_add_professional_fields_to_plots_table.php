<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('plots', function (Blueprint $table) {
            // هنضيف الحقول بعد عمود plot_number عشان نتفادى خطأ "type" اللي فات
            $table->string('image')->nullable()->after('plot_number');
            $table->boolean('is_available')->default(true)->after('status');
            $table->string('location_tag')->nullable()->after('is_available');
        });
    }

    public function down(): void
    {
        Schema::table('plots', function (Blueprint $table) {
            $table->dropColumn(['image', 'is_available', 'location_tag']);
        });
    }
};