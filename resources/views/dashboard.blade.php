@extends('layouts.app')

@section('title', 'لوحة التحكم')
@section('page-title', 'لوحة التحكم')

@section('content')

{{-- ── Stat cards ── --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#1a3a5c,#2563a8)">
            <div class="stat-icon"><i class="bi bi-journal-bookmark-fill"></i></div>
            <div class="stat-num">{{ number_format($stats['total_sales']) }}</div>
            <div class="stat-lbl">إجمالي سجلات البيوع</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#065f46,#059669)">
            <div class="stat-icon"><i class="bi bi-check-circle-fill"></i></div>
            <div class="stat-num">{{ number_format($stats['paid_sales']) }}</div>
            <div class="stat-lbl">مسددة بالكامل</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#991b1b,#dc2626)">
            <div class="stat-icon"><i class="bi bi-x-circle-fill"></i></div>
            <div class="stat-num">{{ number_format($stats['unpaid_sales']) }}</div>
            <div class="stat-lbl">غير مسددة</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#92400e,#d97706)">
            <div class="stat-icon"><i class="bi bi-printer-fill"></i></div>
            <div class="stat-num">{{ $stats['total_certs_today'] }}</div>
            <div class="stat-lbl">إفادات اليوم</div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-pie-chart-fill me-2" style="color:#c8a96e"></i>أنواع البيوع
            </div>
            <div class="card-body d-flex flex-column justify-content-center gap-3 py-4">
                @php
                    $total = $stats['total_sales'] ?: 1;
                    $agPct = round($stats['agricultural'] / $total * 100);
                    $buPct = round($stats['buildings'] / $total * 100);
                @endphp
                <div>
                    <div class="d-flex justify-content-between mb-1">
                        <span style="font-size:.85rem;font-weight:600;color:#374151">🌾 زراعة</span>
                        <span style="font-size:.85rem;color:#64748b">{{ number_format($stats['agricultural']) }} ({{ $agPct }}%)</span>
                    </div>
                    <div class="progress" style="height:10px;border-radius:10px">
                        <div class="progress-bar" style="width:{{ $agPct }}%;background:#059669;border-radius:10px"></div>
                    </div>
                </div>
                <div>
                    <div class="d-flex justify-content-between mb-1">
                        <span style="font-size:.85rem;font-weight:600;color:#374151">🏗️ مباني</span>
                        <span style="font-size:.85rem;color:#64748b">{{ number_format($stats['buildings']) }} ({{ $buPct }}%)</span>
                    </div>
                    <div class="progress" style="height:10px;border-radius:10px">
                        <div class="progress-bar" style="width:{{ $buPct }}%;background:#2563a8;border-radius:10px"></div>
                    </div>
                </div>
                <hr class="my-1">
                <div class="text-center">
                    <small class="text-muted">إجمالي الإفادات المُصدرة: <strong>{{ number_format($stats['total_certs']) }}</strong></small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-geo-alt-fill me-2" style="color:#c8a96e"></i>أكثر المراكز سجلات
            </div>
            <div class="card-body py-3">
                @foreach($topMarkazes as $m)
                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                    <span style="font-size:.9rem;font-weight:600">{{ $m->markaz }}</span>
                    <span class="badge" style="background:#1a3a5c">{{ number_format($m->count) }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-graph-up me-2" style="color:#c8a96e"></i>الإفادات (آخر 14 يوم)
            </div>
            <div class="card-body">
                <canvas id="certsChart" height="170"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- Recent sales --}}
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-clock-history me-2" style="color:#c8a96e"></i>آخر السجلات المضافة</span>
        <a href="{{ route('sales.index') }}" class="btn btn-sm" style="background:#1a3a5c;color:#fff;border-radius:8px;font-size:.8rem;">
            عرض الكل <i class="bi bi-arrow-left ms-1"></i>
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>رقم البيعة</th>
                        <th>المشتري</th>
                        <th>الناحية / المركز</th>
                        <th>النوع</th>
                        <th>الحالة</th>
                        <th>تاريخ الإدخال</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentSales as $sale)
                    <tr onclick="location='{{ route('sales.show', $sale) }}'">
                        <td><strong>{{ $sale->full_sale_number }}</strong></td>
                        <td>{{ $sale->buyer_name }}</td>
                        <td><span class="text-muted">{{ $sale->village }} / {{ $sale->markaz }}</span></td>
                        <td>{{ $sale->type_label }}</td>
                        <td>
                            <span class="badge badge-{{ $sale->payment_status }}">
                                {{ $sale->status_label }}
                            </span>
                        </td>
                        <td style="font-size:.8rem;color:#64748b">{{ $sale->created_at->format('Y/m/d') }}</td>
                        <td>
                            <a href="{{ route('sales.show', $sale) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const labels = @json($dailyCerts->keys());
const data   = @json($dailyCerts->values());

new Chart(document.getElementById('certsChart'), {
    type: 'bar',
    data: {
        labels,
        datasets: [{
            label: 'إفادات',
            data,
            backgroundColor: 'rgba(26,58,92,.7)',
            borderRadius: 6,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            x: { ticks: { font: { family: 'Cairo', size: 10 } } },
            y: { ticks: { font: { family: 'Cairo', size: 11 }, precision: 0 } }
        }
    }
});
</script>
@endpush
