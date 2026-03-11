<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

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
    private function generatePdf(Certificate $certificate): \Illuminate\Http\Response
    {
        $certificate->load('sale', 'issuedBy');

        $pdf = Pdf::loadView('certificates.pdf', compact('certificate'))
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'defaultFont' => 'DejaVu Sans',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled'      => false,
            ]);

        $filename = 'ifada-' . $certificate->certificate_number . '.pdf';

        return $pdf->stream($filename);
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
