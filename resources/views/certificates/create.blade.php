@extends('layouts.app')

@section('title', 'طباعة إفادة - بيعة ' . $sale->full_sale_number)
@section('page-title', 'إصدار إفادة')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('sales.index') }}" style="color:#1a3a5c">السجلات</a></li>
            <li class="breadcrumb-item"><a href="{{ route('sales.show', $sale) }}" style="color:#1a3a5c">بيعة {{ $sale->full_sale_number }}</a></li>
            <li class="breadcrumb-item active">إصدار إفادة</li>
        </ol>
    </nav>
</div>

<div class="row g-3">
    <div class="col-lg-5">
        {{-- Sale summary --}}
        <div class="card mb-3">
            <div class="card-header">
                <i class="bi bi-info-circle-fill me-2" style="color:#c8a96e"></i>ملخص البيعة
            </div>
            <div class="card-body">
                <table class="table table-sm mb-0">
                    <tr><td class="text-muted" style="font-size:.82rem;width:40%">رقم البيعة</td>
                        <td><strong>{{ $sale->full_sale_number }}</strong></td></tr>
                    <tr><td class="text-muted" style="font-size:.82rem">المشتري</td>
                        <td>{{ $sale->buyer_name }}</td></tr>
                    <tr><td class="text-muted" style="font-size:.82rem">الناحية</td>
                        <td>{{ $sale->village }}</td></tr>
                    <tr><td class="text-muted" style="font-size:.82rem">المركز</td>
                        <td>{{ $sale->markaz }}</td></tr>
                    <tr><td class="text-muted" style="font-size:.82rem">النوع</td>
                        <td>{{ $sale->type_label }}</td></tr>
                    <tr><td class="text-muted" style="font-size:.82rem">المساحة</td>
                        <td>{{ $sale->area_description }}</td></tr>
                    <tr><td class="text-muted" style="font-size:.82rem">حالة السداد</td>
                        <td><span class="badge badge-{{ $sale->payment_status }}">{{ $sale->status_label }}</span></td></tr>
                    <tr><td class="text-muted" style="font-size:.82rem">الدفتر / الصفحة</td>
                        <td>{{ $sale->book_number }} / {{ $sale->page_number }}</td></tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-printer-fill me-2" style="color:#c8a96e"></i>تفاصيل الإفادة
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('sales.certificates.store', $sale) }}" target="_blank">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-600">اسم مقدم الطلب <small class="text-muted fw-normal">(اختياري)</small></label>
                        <input type="text" name="applicant_name" class="form-control"
                               placeholder="الاسم الثلاثي لصاحب الطلب" maxlength="200">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-600">الغرض من الإفادة <small class="text-muted fw-normal">(اختياري)</small></label>
                        <input type="text" name="purpose" class="form-control"
                               placeholder="مثال: للتقديم لجهة حكومية" maxlength="500">
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-600">نص الإفادة <span class="text-danger">*</span></label>
                        <div class="alert alert-info py-2 mb-2" style="font-size:.82rem">
                            <i class="bi bi-info-circle me-1"></i>يمكنك تعديل النص أدناه إذا لزم الأمر.
                        </div>
                        <textarea name="certificate_text" id="certText" class="form-control"
                                  rows="6" required style="font-size:.95rem;line-height:2">{{ $text }}</textarea>
                    </div>

                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('sales.show', $sale) }}" class="btn btn-outline-secondary px-4">إلغاء</a>
                        <button type="submit" class="btn fw-bold px-5" style="background:#1a3a5c;color:#fff;border-radius:8px;">
                            <i class="bi bi-file-pdf me-2"></i>إصدار وطباعة الإفادة PDF
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Preview box --}}
        <div class="card mt-3" style="border:2px dashed #c8a96e">
            <div class="card-header" style="background:#fffbf0;font-size:.85rem">
                <i class="bi bi-eye me-1"></i>معاينة نص الإفادة
            </div>
            <div class="card-body">
                <p id="certPreview" style="font-size:.95rem;line-height:2.2;color:#1e293b;text-align:justify">{{ $text }}</p>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const textarea = document.getElementById('certText');
    const preview  = document.getElementById('certPreview');
    textarea.addEventListener('input', () => { preview.textContent = textarea.value; });
</script>
@endpush
