<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Certificate;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_sales'       => Sale::count(),
            'paid_sales'        => Sale::byStatus('paid')->count(),
            'unpaid_sales'      => Sale::byStatus('unpaid')->count(),
            'total_certs_today' => Certificate::whereDate('issued_at', today())->count(),
            'total_certs'       => Certificate::count(),
            'agricultural'      => Sale::ofType('agricultural')->count(),
            'buildings'         => Sale::ofType('buildings')->count(),
        ];

        // Certificates per day for last 14 days
        $dailyCerts = Certificate::selectRaw('DATE(issued_at) as date, COUNT(*) as count')
            ->whereDate('issued_at', '>=', now()->subDays(14))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date');

        // Recent sales
        $recentSales = Sale::with('creator')->latest()->limit(10)->get();

        // Top markazes
        $topMarkazes = Sale::selectRaw('markaz, COUNT(*) as count')
            ->groupBy('markaz')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        return view('dashboard', compact('stats', 'dailyCerts', 'recentSales', 'topMarkazes'));
    }
}
