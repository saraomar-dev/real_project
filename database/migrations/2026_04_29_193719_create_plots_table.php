<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('plots', function (Blueprint $table) {
            $table->id();
            $table->string('plot_number')->unique(); 
            $table->decimal('area_sqm', 8, 2); 
            $table->enum('soil_quality', ['excellent', 'good', 'fair', 'poor']); 
            $table->integer('sunlight_exposure'); 
            $table->enum('status', ['available', 'rented', 'maintenance'])->default('available'); 
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); 
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('plots');
    }
};