<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Sale;
use App\Models\Certificate;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {


                $admin = User::create([
            'name'      => 'مدير النظام',
            'email'     => 'admin@amlaak.gov.eg',
            'password'  => Hash::make('Admin@1234'),
            'role'      => 'admin',
            'is_active' => true,
        ]);

    Sale::factory()->count(500)->create();



        
        // ── Admin user ──

        // ── Editor user ──
User::updateOrCreate(
    ['email' => 'admin@amlaak.gov.eg'],
    [
        'name' => 'مدير النظام',
        'password' => bcrypt('12345678'),
        'role' => 'admin',
        'is_active' => 1
    ]
);

        // ── Sample sales ──
        $samples = [
            [
                'sale_number'    => '1245',
                'sale_letter'    => 'أ',
                'buyer_name'     => 'محمد أحمد إبراهيم السيد',
                'markaz'         => 'قنا',
                'village'        => 'الكرنك',
                'basin_name'     => 'حوض الجبل',
                'sale_type'      => 'agricultural',
                'area_feddan'    => 5,
                'area_qirat'     => 12,
                'area_sahm'      => 8,
                'payment_status' => 'paid',
                'book_number'    => '3',
                'page_number'    => '47',
                'notes'          => 'تم السداد في عام 1998',
                'created_by'     => $admin->id,
            ],
            [
                'sale_number'    => '678',
                'sale_letter'    => 'ب',
                'buyer_name'     => 'فاطمة علي حسن محمود',
                'markaz'         => 'لوكسور',
                'village'        => 'البياضية',
                'sale_type'      => 'buildings',
                'area_sqm'       => 250.00,
                'payment_status' => 'unpaid',
                'book_number'    => '7',
                'page_number'    => '112',
                'created_by'     => $admin->id,
            ],
            [
                'sale_number'    => '3301',
                'buyer_name'     => 'أحمد عبدالله محمد النجار',
                'markaz'         => 'إسنا',
                'village'        => 'الرزيقات',
                'basin_name'     => 'حوض النخيل',
                'sale_type'      => 'agricultural',
                'area_feddan'    => 2,
                'area_qirat'     => 0,
                'area_sahm'      => 0,
                'payment_status' => 'unknown',
                'book_number'    => '1',
                'page_number'    => '23',
                'created_by'     => $admin->id,
            ],
        ];

        foreach ($samples as $s) {
            Sale::create($s);
        }

        echo "✅ Database seeded successfully!\n";
        echo "   Admin:  admin@amlaak.gov.eg / Admin@1234\n";
        echo "   Editor: editor@amlaak.gov.eg / Editor@1234\n";
    }
}
