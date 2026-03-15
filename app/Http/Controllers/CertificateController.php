<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use ArPHP\I18N\Arabic; // أضف هذا السطر في الأعلى

class CertificateController extends Controller
{
    /**
     * Show the print preview / confirmation form
     */
    public function create(Sale $sale)
    {
        $text = Certificate::buildText($sale);
        return view('certificates.create', compact('sale', 'text'));
    }

    /**
     * Store and immediately return the PDF
     */
    public function store(Request $request, Sale $sale)
    {
        $request->validate([
            'applicant_name' => ['nullable', 'string', 'max:200'],
            'purpose'        => ['nullable', 'string', 'max:500'],
            'certificate_text' => ['required', 'string'],
        ]);

        $certificate = Certificate::create([
            'sale_id'          => $sale->id,
            'issued_by'        => Auth::id(),
            'certificate_number' => Certificate::generateNumber(),
            'applicant_name'   => $request->applicant_name,
            'purpose'          => $request->purpose,
            'certificate_text' => $request->certificate_text,
                'recipient'        => $request->recipient, // <-- هنا

            'issued_at'        => now(),
        ]);

        return $this->generateCertificateView($certificate);
    }

    /**
     * Re-print an existing certificate
     */
    public function show(Certificate $certificate)
    {
        return $this->generateCertificateView($certificate);
    }

    /**
     * Generate and stream the PDF
     */

// امسح سطر use ArPHP\I18N\Arabic; 

private function generateCertificateView(Certificate $certificate)
{
    // تحميل العلاقات
    $certificate->load('sale', 'issuedBy');

    // معالجة النصوص العربية إذا كانت مكتبة ArPHP موجودة
    if (class_exists(\ArPHP\I18N\Arabic::class)) {
        $arabic = new \ArPHP\I18N\Arabic();

        if (!empty($certificate->certificate_text)) {
            $certificate->certificate_text = $arabic->utf8Glyphs($certificate->certificate_text);
        }

        if ($certificate->sale) {
            $certificate->sale->buyer_name = $arabic->utf8Glyphs($certificate->sale->buyer_name ?? '');
            $certificate->sale->village    = $arabic->utf8Glyphs($certificate->sale->village ?? '');
            $certificate->sale->markaz     = $arabic->utf8Glyphs($certificate->sale->markaz ?? '');
        }
    }

    // نص الجهة حسب اختيار المستخدم عند الإنشاء
    $recipientText = '';
    switch ($certificate->recipient ?? '') {
        case 'local_unit':
            $recipientText = "السيد المهندس/ رئيس الوحدة المحلية لمركز ومدينة " . ($certificate->sale->markaz ?? '');
            break;
        case 'agric_bank':
            $recipientText = "السيد الأستاذ/ مدير عام البنك الزراعي المصري فرع " . ($certificate->sale->village ?? '');
            break;
        case 'agriculture':
            $recipientText = "السيد المهندس/ وكيل الزراعة - مدير مديرية الزراعة بقنا";
            break;
        default:
            $recipientText = "جهة غير محددة";
    }

    // عرض الإفادة مباشرة على صفحة ويب
    return view('certificates.pdf', [
        'certificate' => $certificate,
        'recipientText' => $recipientText,
    ]);
}



    /**
     * Daily stats for dashboard
     */
    public function dailyStats()
    {
        return Certificate::selectRaw('DATE(issued_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderByDesc('date')
            ->limit(30)
            ->get();
    }
}
