@extends('layouts.app')

@section('title', 'البحث في سجلات البيوع')
@section('page-title', 'البحث في سجلات البيوع')

@section('content')

{{-- Search card --}}
<div class="search-card mb-4">
    <form method="GET" action="{{ route('sales.index') }}">
        <div class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label text-white mb-1" style="font-size:.85rem;font-weight:600;">
                    <i class="bi bi-search me-1"></i>بحث عام
                </label>
                <input type="text" name="q" class="form-control"
                       value="{{ $filters['q'] ?? '' }}"
                       placeholder="رقم البيعة / اسم المشتري / القرية / المركز">
            </div>
            <div class="col-md-2">
                <label class="form-label text-white mb-1" style="font-size:.85rem;">المركز</label>
                <input type="text" name="markaz" class="form-control"
                       value="{{ $filters['markaz'] ?? '' }}" placeholder="مثال: دشنا">
            </div>
            <div class="col-md-2">
                <label class="form-label text-white mb-1" style="font-size:.85rem;">الناحية / القرية</label>
                <input type="text" name="village" class="form-control"
                       value="{{ $filters['village'] ?? '' }}" placeholder="مثال: المحروسه">
            </div>
            <div class="col-md-2">
                <label class="form-label text-white mb-1" style="font-size:.85rem;">نوع البيعة</label>
                <select name="type" class="form-select">
                    <option value="">الكل</option>
                    @foreach($types as $key => $label)
                        <option value="{{ $key }}" {{ ($filters['type'] ?? '') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-1">
                <label class="form-label text-white mb-1" style="font-size:.85rem;">الحالة</label>
                <select name="status" class="form-select">
                    <option value="">الكل</option>
                    @foreach($statuses as $key => $label)
                        <option value="{{ $key }}" {{ ($filters['status'] ?? '') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-1 d-flex gap-2">
                <button type="submit" class="btn btn-warning fw-bold w-100" style="border-radius:8px;">
                    <i class="bi bi-search"></i>
                </button>
                <a href="{{ route('sales.index') }}" class="btn btn-outline-light" style="border-radius:8px;" title="مسح">
                    <i class="bi bi-x-lg"></i>
                </a>
            </div>
        </div>
    </form>
</div>

{{-- Results --}}
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>
            <i class="bi bi-table me-2" style="color:#c8a96e"></i>
            نتائج البحث &mdash;
            <span class="text-muted" style="font-size:.85rem;font-weight:400;">
                {{ number_format($sales->total()) }} سجل
            </span>
        </span>
        @if(auth()->user()->isEditor())
        <a href="{{ route('sales.create') }}" class="btn btn-sm fw-bold" style="background:#c8a96e;color:#1a3a5c;border-radius:8px;font-size:.8rem;">
            <i class="bi bi-plus-lg me-1"></i>إضافة سجل
        </a>
        @endif
    </div>
    <div class="card-body p-0">
        @if($sales->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="bi bi-inbox" style="font-size:3rem;display:block;margin-bottom:.5rem;"></i>
                لا توجد نتائج مطابقة للبحث
            </div>
        @else
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>رقم البيعة</th>
                        <th>اسم المشتري</th>
                        <th>الناحية</th>
                        <th>المركز</th>
                        <th>النوع</th>
                        <th>المساحة</th>
                        <th>الدفتر/الصفحة</th>
                        <th>حالة السداد</th>
                        <th>صورة</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sales as $sale)
                    <tr onclick="location='{{ route('sales.show', $sale) }}'">
                        <td><strong style="color:#1a3a5c">{{ $sale->full_sale_number }}</strong></td>
                        <td>{{ $sale->buyer_name }}</td>
                        <td>{{ $sale->village }}</td>
                        <td>{{ $sale->markaz }}</td>
                        <td>
                            <span class="badge" style="background:#e2e8f0;color:#374151;font-size:.78rem;">
                                {{ $sale->type_label }}
                            </span>
                        </td>
                        <td style="font-size:.82rem;">{{ $sale->area_description }}</td>
                        <td style="font-size:.8rem;color:#64748b">
                            {{ $sale->book_number }} / {{ $sale->page_number }}
                        </td>
                        <td>
                            <span class="badge badge-{{ $sale->payment_status }}">
                                {{ $sale->status_label }}
                            </span>
                        </td>
<td>
    @if($sale->scan_path)
        <a href="{{ asset('storage/' . $sale->scan_path) }}" target="_blank">
            <img src="{{ asset('storage/' . $sale->scan_path) }}" 
                 alt="Scan" 
                 style="max-width:50px; max-height:50px; border-radius:4px;">
        </a>
    @else
        <i class="bi bi-image" style="color:#cbd5e1" title="لا توجد صورة"></i>
    @endif
</td>
                        <td onclick="event.stopPropagation()" class="text-nowrap">
                            <a href="{{ route('sales.show', $sale) }}" class="btn btn-sm btn-outline-primary me-1" title="عرض">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('sales.certificates.create', $sale) }}" class="btn btn-sm btn-outline-success" title="طباعة إفادة">
                                <i class="bi bi-printer"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="d-flex justify-content-between align-items-center p-3">
            <small class="text-muted">
                عرض {{ $sales->firstItem() }}–{{ $sales->lastItem() }} من {{ number_format($sales->total()) }}
            </small>
            {{ $sales->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
</div>

@endsection
