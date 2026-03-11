<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Sale extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'sale_number', 'sale_letter', 'buyer_name', 'buyer_name_normalized',
        'markaz', 'village', 'basin_name', 'sale_type',
        'area_feddan', 'area_qirat', 'area_sahm', 'area_sqm',
        'payment_status', 'book_number', 'page_number',
        'notes', 'scan_path', 'scan_original_name',
        'created_by', 'updated_by',
    ];

    protected $casts = [
        'area_feddan' => 'integer',
        'area_qirat'  => 'integer',
        'area_sahm'   => 'integer',
        'area_sqm'    => 'float',
    ];

    // ─── Constants ─────────────────────────────────────────────────────────────

    const TYPE_AGRICULTURAL = 'agricultural';
    const TYPE_BUILDINGS     = 'buildings';

    const STATUS_PAID    = 'paid';
    const STATUS_UNPAID  = 'unpaid';
    const STATUS_UNKNOWN = 'unknown';

    public static array $typeLabels = [
        self::TYPE_AGRICULTURAL => 'زراعة',
        self::TYPE_BUILDINGS    => 'مباني',
    ];

    public static array $statusLabels = [
        self::STATUS_PAID    => 'مسدد بالكامل',
        self::STATUS_UNPAID  => 'غير مسدد',
        self::STATUS_UNKNOWN => 'غير معروف',
    ];

    public static array $statusColors = [
        self::STATUS_PAID    => 'success',
        self::STATUS_UNPAID  => 'danger',
        self::STATUS_UNKNOWN => 'warning',
    ];

    // ─── Accessors ──────────────────────────────────────────────────────────────

    public function getFullSaleNumberAttribute(): string
    {
        return ($this->sale_letter ?? '') . $this->sale_number;
    }

    public function getTypeLabelAttribute(): string
    {
        return self::$typeLabels[$this->sale_type] ?? $this->sale_type;
    }

    public function getStatusLabelAttribute(): string
    {
        return self::$statusLabels[$this->payment_status] ?? $this->payment_status;
    }

    public function getStatusColorAttribute(): string
    {
        return self::$statusColors[$this->payment_status] ?? 'secondary';
    }

    public function getAreaDescriptionAttribute(): string
    {
        if ($this->sale_type === self::TYPE_AGRICULTURAL) {
            $parts = [];
            if ($this->area_feddan) $parts[] = $this->area_feddan . ' فدان';
            if ($this->area_qirat)  $parts[] = $this->area_qirat . ' قيراط';
            if ($this->area_sahm)   $parts[] = $this->area_sahm . ' سهم';
            return implode(' و', $parts) ?: '—';
        }
        return $this->area_sqm > 0 ? number_format($this->area_sqm, 2) . ' م²' : '—';
    }

    public function getScanUrlAttribute(): ?string
    {
        return $this->scan_path ? asset('storage/' . $this->scan_path) : null;
    }

    // ─── Relationships ──────────────────────────────────────────────────────────

    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // ─── Scopes ─────────────────────────────────────────────────────────────────

    public function scopeSearch(Builder $q, string $term): Builder
    {
        $term = trim($term);
        return $q->where(function (Builder $sub) use ($term) {
            $sub->where('sale_number', 'LIKE', "%{$term}%")
                ->orWhere('sale_letter', 'LIKE', "%{$term}%")
                ->orWhere('buyer_name', 'LIKE', "%{$term}%")
                ->orWhere('buyer_name_normalized', 'LIKE', "%{$term}%")
                ->orWhere('markaz', 'LIKE', "%{$term}%")
                ->orWhere('village', 'LIKE', "%{$term}%");
        });
    }

    public function scopeOfType(Builder $q, string $type): Builder
    {
        return $q->where('sale_type', $type);
    }

    public function scopeByStatus(Builder $q, string $status): Builder
    {
        return $q->where('payment_status', $status);
    }

    // ─── Mutators ───────────────────────────────────────────────────────────────

    public function setBuyerNameAttribute(string $value): void
    {
        $this->attributes['buyer_name'] = $value;
        $this->attributes['buyer_name_normalized'] = self::normalizeArabic($value);
    }

    // ─── Static helpers ─────────────────────────────────────────────────────────

    /**
     * Normalize Arabic text for better search (remove tashkeel, normalize alef/ya/ta marbuta)
     */
    public static function normalizeArabic(string $text): string
    {
        // Remove tashkeel (harakat)
        $text = preg_replace('/[\x{064B}-\x{065F}\x{0670}]/u', '', $text);
        // Normalize alef forms to ا
        $text = preg_replace('/[إأآ]/u', 'ا', $text);
        // Normalize ya (ى → ي)
        $text = str_replace('ى', 'ي', $text);
        // Normalize ta marbuta (ة → ه)
        $text = str_replace('ة', 'ه', $text);
        // Remove extra spaces
        $text = preg_replace('/\s+/', ' ', trim($text));
        return $text;
    }
}
