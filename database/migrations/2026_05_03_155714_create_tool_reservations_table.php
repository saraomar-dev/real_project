<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tool_reservations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tool_id')->constrained()->onDelete('cascade');

            $table->string('user_name');
            $table->date('reservation_date');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tool_reservations');
    }
};