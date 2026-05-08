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
    Schema::create('inspections', function (Blueprint $table) {
        $table->id();
        // ربط المعاينة بالأرض (Plot)
        $table->foreignId('plot_id')->constrained()->onDelete('cascade');
        // ربط المعاينة بالواردن اللي عملها (User)
        $table->foreignId('user_id')->constrained('users');
        
        $table->string('status'); // الحالة: (Excellent, Needs Attention, Pest Detected)
        $table->text('notes'); // ملاحظات الواردن عن حالة التربة والزرع
        $table->boolean('has_pests')->default(false); // هل فيه آفات؟
        
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspections');
    }
};
