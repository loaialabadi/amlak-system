<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained('sales')->cascadeOnDelete();
            $table->foreignId('issued_by')->constrained('users');
            $table->string('certificate_number', 50)->unique()->comment('رقم الإفادة');
            $table->string('applicant_name', 200)->nullable()->comment('اسم مقدم الطلب');
            $table->string('purpose', 500)->nullable()->comment('الغرض من الإفادة');
            $table->text('certificate_text')->comment('نص الإفادة');
            $table->string('recipient')->nullable();
            $table->timestamp('issued_at')->useCurrent();
            
            // عمود افتراضي لحفظ التاريخ فقط
            $table->date('issued_date')->storedAs('DATE(`issued_at`)');
            
            $table->timestamps();

            // indexes
            $table->index('sale_id');
            $table->index('issued_by');
            $table->index('issued_at');
            $table->index('issued_date'); // كافٍ
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};