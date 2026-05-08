<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('damage_reports', function (Blueprint $table) {

            if (!Schema::hasColumn('damage_reports', 'status')) {
                $table->string('status')->default('pending');
            }

        });
    }

    public function down(): void
    {
        Schema::table('damage_reports', function (Blueprint $table) {

            if (Schema::hasColumn('damage_reports', 'status')) {
                $table->dropColumn('status');
            }

        });
    }
};