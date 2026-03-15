<?php

namespace Database\Factories;

use App\Models\Sale;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

class SaleFactory extends Factory
{
    protected $model = Sale::class;

    public function definition(): array
    {
        $buyer = $this->faker->name();

        // اسم المجلد لكل بيع
        $folder = 'scans/' . preg_replace('/[^A-Za-z0-9\x{0600}-\x{06FF}]/u', '_', $buyer) . '_' . rand(1, 9999);
        Storage::disk('public')->makeDirectory($folder);

        // نسخ الصورة الافتراضية لكل سجل
        $defaultImage = storage_path('app/public/default/scan.jpg'); // ضع الصورة هنا
        $filename = 'scan.jpg';
        Storage::disk('public')->put($folder.'/'.$filename, file_get_contents($defaultImage));

        return [
            'sale_number' => $this->faker->numberBetween(1000, 9999),
            'sale_letter' => $this->faker->randomElement(['أ','ب','ج', null]),
            'buyer_name' => $buyer,
            'buyer_name_normalized' => Sale::normalizeArabic($buyer),
            'markaz' => $this->faker->randomElement(['قنا','قفط','دشنا','نجع حمادي']),
            'village' => $this->faker->city(),
            'basin_name' => $this->faker->word(),
            'sale_type' => $this->faker->randomElement([Sale::TYPE_AGRICULTURAL, Sale::TYPE_BUILDINGS]),
            'area_feddan' => rand(0,5),
            'area_qirat'  => rand(0,23),
            'area_sahm'   => rand(0,23),
            'area_sqm'    => rand(0,300),
            'payment_status' => $this->faker->randomElement([Sale::STATUS_PAID, Sale::STATUS_UNPAID, Sale::STATUS_UNKNOWN]),
            'book_number' => rand(1,50),
            'page_number' => rand(1,400),
            'notes' => $this->faker->sentence(),
            'scan_path' => $folder.'/'.$filename,
            'scan_original_name' => $filename,
            'created_by' => 1,
        ];
    }
}