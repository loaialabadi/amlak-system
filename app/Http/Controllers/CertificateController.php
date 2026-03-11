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
            'issued_at'        => now(),
        ]);

        return $this->generatePdf($certificate);
    }

    /**
     * Re-print an existing certificate
     */
    public function show(Certificate $certificate)
    {
        return $this->generatePdf($certificate);
    }

    /**
     * Generate and stream the PDF
     */

// امسح سطر use ArPHP\I18N\Arabic; 

private function generatePdf(Certificate $certificate): \Illuminate\Http\Response
{
    $certificate->load('sale', 'issuedBy');

    // معالجة النصوص العربية
    if (class_exists(\ArPHP\I18N\Arabic::class)) {

        $arabic = new \ArPHP\I18N\Arabic();

        // معالجة النص الأساسي
        if (!empty($certificate->certificate_text)) {
            $certificate->certificate_text = $arabic->utf8Glyphs($certificate->certificate_text);
        }

        // معالجة بيانات البيع
        if ($certificate->sale) {

            if (!empty($certificate->sale->buyer_name)) {
                $certificate->sale->buyer_name = $arabic->utf8Glyphs($certificate->sale->buyer_name);
            }

            if (!empty($certificate->sale->village)) {
                $certificate->sale->village = $arabic->utf8Glyphs($certificate->sale->village);
            }

            if (!empty($certificate->sale->markaz)) {
                $certificate->sale->markaz = $arabic->utf8Glyphs($certificate->sale->markaz);
            }
        }
    }

    // إنشاء الـ PDF
    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('certificates.pdf', [
        'certificate' => $certificate
    ]);

    $pdf->setPaper('A4', 'portrait');

    $pdf->setOptions([
        'defaultFont' => 'DejaVu Sans',
        'isHtml5ParserEnabled' => true,
        'isRemoteEnabled' => true
    ]);

    return $pdf->stream('ifada-' . $certificate->certificate_number . '.pdf');
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
