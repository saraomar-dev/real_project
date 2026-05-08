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
    Schema::table('plots', function (Blueprint $table) {
        // إضافة عمود السعر ونوعه decimal عشان الكسور
        $table->decimal('price', 10, 2)->default(330.00)->after('area_sqm');
    });
}

public function down()
{
    Schema::table('plots', function (Blueprint $table) {
        // لحذف العمود لو عملتي rollback
        $table->dropColumn('price');
    });
}
};
