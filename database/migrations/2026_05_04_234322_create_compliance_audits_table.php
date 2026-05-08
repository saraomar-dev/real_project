<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('compliance_audits', function (Blueprint $table) {
            $table->id();
            // ربط الأرض (Plot) اللي الشخص التاني (إنتي) مسؤولة عنها
            $table->foreignId('plot_id')->constrained('plots')->onDelete('cascade');
            
            // ربط الحارس (Warden) من جدول اليوزرز
            $table->foreignId('warden_id')->constrained('users')->onDelete('cascade');
            
            // حالة الامتثال (ملتزم أو مخالف) كما في متطلباتك
            $table->enum('status', ['compliant', 'violation'])->default('compliant');
            
            // ملاحظات التفتيش وصورة المعاينة (مهمة للـ Documentation)
            $table->text('notes')->nullable();
            $table->string('inspection_image')->nullable(); 
            
            $table->date('inspection_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('compliance_audits');
    }
};