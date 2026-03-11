<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Certificate extends Model
{
    protected $fillable = [
        'sale_id', 'issued_by', 'certificate_number',
        'applicant_name', 'purpose', 'certificate_text', 'issued_at',
    ];

    protected $casts = [
        'issued_at' => 'datetime',
    ];

    // ─── Relationships ──────────────────────────────────────────────────────────

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    public function issuedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    // ─── Static helpers ─────────────────────────────────────────────────────────

    public static function generateNumber(): string
    {
        $year  = now()->format('Y');
        $count = self::whereYear('issued_at', $year)->count() + 1;
        return sprintf('CRT-%s-%05d', $year, $count);
    }

    public static function buildText(Sale $sale): string
    {
        $area = $sale->area_description;
        $type = $sale->type_label;

        $statusText = match($sale->payment_status) {
            'paid'    => 'مسددة بالكامل ولا يوجد عليها أي مستحقات',
            'unpaid'  => 'غير مسددة وعليها مستحقات لم تُسوَّ حتى تاريخه',
            default   => 'غير محددة الحالة في سجلاتنا وتستلزم مراجعة الجهة المختصة',
        };

        $basinText = $sale->basin_name ? " بحوض {$sale->basin_name}" : '';

        return "بالبحث في سجلات أملاك الدولة تبين أن البيعة رقم ({$sale->full_sale_number}) " .
               "من نوع ({$type}) بمساحة ({$area}){$basinText} " .
               "بناحية ({$sale->village}) مركز ({$sale->markaz}) " .
               "مباعة باسم المواطن ({$sale->buyer_name}) " .
               "وأنها {$statusText}.";
    }
}
