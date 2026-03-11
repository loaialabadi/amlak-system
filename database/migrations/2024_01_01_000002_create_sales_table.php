<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();

            // بيانات البيعة الأساسية
            $table->string('sale_number', 50)->comment('رقم البيعة');
            $table->string('sale_letter', 10)->nullable()->comment('الحرف');
            $table->string('full_sale_number', 60)->virtualAs("CONCAT(IFNULL(sale_letter,''), sale_number)")->comment('رقم البيعة الكامل');

            // بيانات المشتري
            $table->string('buyer_name', 200)->comment('اسم المشتري');
            $table->string('buyer_name_normalized', 200)->nullable()->comment('اسم المشتري بعد التطبيع للبحث');

            // الموقع الجغرافي
            $table->string('markaz', 100)->comment('المركز');
            $table->string('village', 100)->comment('القرية / الناحية');
            $table->string('basin_name', 100)->nullable()->comment('اسم الحوض - للأراضي الزراعية');

            // نوع البيعة والمساحة
            $table->enum('sale_type', ['agricultural', 'buildings'])->comment('نوع البيعة: زراعة أو مباني');

            // مساحة الأرض الزراعية
            $table->unsignedSmallInteger('area_feddan')->default(0)->comment('فدان');
            $table->unsignedSmallInteger('area_qirat')->default(0)->comment('قيراط (0-23)');
            $table->unsignedSmallInteger('area_sahm')->default(0)->comment('سهم (0-23)');

            // مساحة أرض المباني
            $table->decimal('area_sqm', 10, 2)->default(0)->comment('متر مربع');

            // حالة السداد
            $table->enum('payment_status', ['paid', 'unpaid', 'unknown'])
                ->default('unknown')
                ->comment('حالة السداد: مسدد / غير مسدد / غير معروف');

            // مرجع الدفتر
            $table->string('book_number', 50)->comment('رقم الدفتر');
            $table->string('page_number', 20)->comment('رقم الصفحة');

            // ملاحظات
            $table->text('notes')->nullable()->comment('ملاحظات');

            // الأرشفة الرقمية
            $table->string('scan_path')->nullable()->comment('مسار صورة الصفحة الممسوحة');
            $table->string('scan_original_name')->nullable()->comment('اسم الملف الأصلي');

            // بيانات الإدخال
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();

            // Indexes للبحث السريع
            $table->index('sale_number');
            $table->index('sale_letter');
            $table->index('buyer_name');
            $table->index('buyer_name_normalized');
            $table->index('markaz');
            $table->index('village');
            $table->index('sale_type');
            $table->index('payment_status');
            $table->index('book_number');
            $table->index(['markaz', 'village']);
            $table->index(['sale_number', 'sale_letter']);
            $table->index('created_at');

            // Full-text search index
            $table->fullText(['buyer_name', 'buyer_name_normalized', 'markaz', 'village', 'notes'], 'sales_fulltext');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
