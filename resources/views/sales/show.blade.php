@extends('layouts.app')

@section('title', 'بيعة رقم ' . $sale->full_sale_number)
@section('page-title', 'عرض سجل البيعة')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('sales.index') }}" style="color:#1a3a5c">السجلات</a></li>
            <li class="breadcrumb-item active">بيعة {{ $sale->full_sale_number }}</li>
        </ol>
    </nav>
    <div class="d-flex gap-2">
        @if(auth()->user()->isEditor())
        <a href="{{ route('sales.edit', $sale) }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-pencil me-1"></i>تعديل
        </a>
        @endif
        <a href="{{ route('sales.certificates.create', $sale) }}" class="btn btn-sm fw-bold"
           style="background:#1a3a5c;color:#fff;border-radius:8px;">
            <i class="bi bi-printer-fill me-1"></i>طباعة إفادة
        </a>
    </div>
</div>

<div class="row g-3">
    {{-- ── Main data ── --}}
    <div class="col-lg-7">
        <div class="card mb-3">
            <div class="card-header">
                <i class="bi bi-file-earmark-text-fill me-2" style="color:#c8a96e"></i>بيانات البيعة
                <span class="badge ms-2 badge-{{ $sale->payment_status }} float-start">
                    {{ $sale->status_label }}
                </span>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="detail-item">
                            <span class="detail-label">رقم البيعة</span>
                            <span class="detail-value fs-4 fw-bold" style="color:#1a3a5c">{{ $sale->full_sale_number }}</span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="detail-item">
                            <span class="detail-label">نوع البيعة</span>
                            <span class="detail-value">
                                <span class="badge" style="background:#e2e8f0;color:#374151;font-size:.9rem;">
                                    {{ $sale->type_label }}
                                </span>
                            </span>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="detail-item">
                            <span class="detail-label">اسم المشتري</span>
                            <span class="detail-value fs-5 fw-bold">{{ $sale->buyer_name }}</span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="detail-item">
                            <span class="detail-label">الناحية / القرية</span>
                            <span class="detail-value">{{ $sale->village }}</span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="detail-item">
                            <span class="detail-label">المركز</span>
                            <span class="detail-value">{{ $sale->markaz }}</span>
                        </div>
                    </div>

                    {{-- Area --}}
                    @if($sale->sale_type === 'agricultural')
                    <div class="col-sm-6">
                        <div class="detail-item">
                            <span class="detail-label">المساحة</span>
                            <span class="detail-value">{{ $sale->area_description }}</span>
                        </div>
                    </div>
                    @if($sale->basin_name)
                    <div class="col-sm-6">
                        <div class="detail-item">
                            <span class="detail-label">اسم الحوض</span>
                            <span class="detail-value">{{ $sale->basin_name }}</span>
                        </div>
                    </div>
                    @endif
                    @else
                    <div class="col-sm-6">
                        <div class="detail-item">
                            <span class="detail-label">المساحة</span>
                            <span class="detail-value">{{ $sale->area_description }}</span>
                        </div>
                    </div>
                    @endif

                    <div class="col-sm-6">
                        <div class="detail-item">
                            <span class="detail-label">رقم الدفتر</span>
                            <span class="detail-value">{{ $sale->book_number }}</span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="detail-item">
                            <span class="detail-label">رقم الصفحة</span>
                            <span class="detail-value">{{ $sale->page_number }}</span>
                        </div>
                    </div>

                    @if($sale->notes)
                    <div class="col-12">
                        <div class="detail-item">
                            <span class="detail-label">ملاحظات</span>
                            <span class="detail-value" style="white-space:pre-line">{{ $sale->notes }}</span>
                        </div>
                    </div>
                    @endif

                    <div class="col-sm-6">
                        <div class="detail-item">
                            <span class="detail-label">أُدخل بواسطة</span>
                            <span class="detail-value text-muted" style="font-size:.85rem;">
                                {{ $sale->creator?->name ?? '—' }}
                                &mdash; {{ $sale->created_at->format('Y/m/d H:i') }}
                            </span>
                        </div>
                    </div>
                    @if($sale->updater)
                    <div class="col-sm-6">
                        <div class="detail-item">
                            <span class="detail-label">آخر تعديل</span>
                            <span class="detail-value text-muted" style="font-size:.85rem;">
                                {{ $sale->updater->name }}
                                &mdash; {{ $sale->updated_at->format('Y/m/d H:i') }}
                            </span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Certificates history --}}
        <div class="card">
            <div class="card-header">
                <i class="bi bi-printer-fill me-2" style="color:#c8a96e"></i>
                الإفادات المُصدرة
                <span class="badge ms-2" style="background:#1a3a5c">{{ $sale->certificates->count() }}</span>
            </div>
            <div class="card-body p-0">
                @if($sale->certificates->isEmpty())
                    <div class="text-center py-4 text-muted" style="font-size:.9rem;">
                        لم يُصدر لهذه البيعة أي إفادة بعد
                    </div>
                @else
                <table class="table mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>رقم الإفادة</th>
                            <th>مقدم الطلب</th>
                            <th>أُصدرت بواسطة</th>
                            <th>التاريخ</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sale->certificates->sortByDesc('issued_at') as $cert)
                        <tr>
                            <td><strong>{{ $cert->certificate_number }}</strong></td>
                            <td>{{ $cert->applicant_name ?? '—' }}</td>
                            <td style="font-size:.82rem;">{{ $cert->issuedBy->name }}</td>
                            <td style="font-size:.82rem;color:#64748b;">{{ $cert->issued_at->format('Y/m/d H:i') }}</td>
                            <td>
                                <a href="{{ route('certificates.show', $cert) }}" target="_blank"
                                   class="btn btn-sm btn-outline-success" title="إعادة طباعة">
                                    <i class="bi bi-file-pdf"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>
    </div>

    {{-- ── Scan image ── --}}
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-image-fill me-2" style="color:#c8a96e"></i>
                صورة الصفحة الأصلية من الدفتر
            </div>
            <div class="card-body text-center">
                @if($sale->scan_path)
                    @php $ext = strtolower(pathinfo($sale->scan_path, PATHINFO_EXTENSION)); @endphp
                    @if($ext === 'pdf')
                        <div class="d-flex flex-column align-items-center gap-3">
                            <i class="bi bi-file-earmark-pdf" style="font-size:5rem;color:#dc2626"></i>
                            <p class="text-muted" style="font-size:.88rem;">{{ $sale->scan_original_name }}</p>
                            <a href="{{ $sale->scan_url }}" target="_blank" class="btn" style="background:#1a3a5c;color:#fff;border-radius:8px;">
                                <i class="bi bi-eye me-2"></i>فتح الملف
                            </a>
                        </div>
                    @else
                        <a href="{{ $sale->scan_url }}" target="_blank" data-bs-toggle="modal" data-bs-target="#scanModal">
                            <img src="{{ $sale->scan_url }}" alt="صورة الدفتر"
                                 class="scan-preview img-fluid" style="max-height:500px">
                        </a>
                        <p class="text-muted mt-2" style="font-size:.78rem;">
                            <i class="bi bi-zoom-in me-1"></i>انقر للتكبير
                        </p>
                        <a href="{{ $sale->scan_url }}" download class="btn btn-sm btn-outline-secondary mt-1">
                            <i class="bi bi-download me-1"></i>تنزيل الصورة
                        </a>
                    @endif
                @else
                    <div class="py-5 text-muted">
                        <i class="bi bi-image" style="font-size:3rem;display:block;margin-bottom:.5rem;opacity:.3"></i>
                        لم يتم رفع صورة لهذا السجل
                        @if(auth()->user()->isEditor())
                        <br>
                        <a href="{{ route('sales.edit', $sale) }}" class="btn btn-sm btn-outline-primary mt-3">
                            <i class="bi bi-upload me-1"></i>رفع صورة
                        </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Scan lightbox modal --}}
@if($sale->scan_path)
<div class="modal fade" id="scanModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content" style="background:transparent;border:none">
            <div class="modal-body p-0 text-center">
                <img src="{{ $sale->scan_url }}" class="img-fluid rounded-3" style="max-height:90vh">
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">إغلاق</button>
                <a href="{{ $sale->scan_url }}" download class="btn btn-outline-light">تنزيل</a>
            </div>
        </div>
    </div>
</div>
@endif

@endsection

@push('styles')
<style>
    .detail-item {
        display: flex;
        flex-direction: column;
        gap: 3px;
        padding: .4rem 0;
        border-bottom: 1px dashed #f1f5f9;
    }
    .detail-label {
        font-size: .75rem;
        color: #94a3b8;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .5px;
    }
    .detail-value {
        font-size: .95rem;
        color: #1e293b;
        font-weight: 500;
    }
</style>
@endpush
