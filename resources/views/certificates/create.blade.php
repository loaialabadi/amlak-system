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
    <!-- Left: Sale Summary -->
    <div class="col-lg-5">
        <div class="card mb-3 shadow-sm">
            <div class="card-header bg-primary text-white">
                <i class="bi bi-info-circle-fill me-2"></i>ملخص البيعة
            </div>
            <div class="card-body">
                <table class="table table-sm mb-0">
                    <tr><td class="text-muted" style="width:40%">رقم البيعة</td><td><strong>{{ $sale->full_sale_number }}</strong></td></tr>
                    <tr><td class="text-muted">المشتري</td><td>{{ $sale->buyer_name }}</td></tr>
                    <tr><td class="text-muted">الناحية</td><td>{{ $sale->village }}</td></tr>
                    <tr><td class="text-muted">المركز</td><td>{{ $sale->markaz }}</td></tr>
                    <tr><td class="text-muted">النوع</td><td>{{ $sale->type_label }}</td></tr>
                    <tr><td class="text-muted">المساحة</td><td>{{ $sale->area_description }}</td></tr>
                    <tr><td class="text-muted">حالة السداد</td><td><span class="badge badge-{{ $sale->payment_status }}">{{ $sale->status_label }}</span></td></tr>
                    <tr><td class="text-muted">الدفتر / الصفحة</td><td>{{ $sale->book_number }} / {{ $sale->page_number }}</td></tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Right: Certificate Form -->
    <div class="col-lg-7">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <i class="bi bi-printer-fill me-2"></i>تفاصيل الإفادة
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('sales.certificates.store', $sale) }}" target="_blank">
                    @csrf

                    <!-- Recipient selection -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">اختر الجهة المستفيدة <span class="text-danger">*</span></label>
                        <select name="recipient" class="form-select" required>
                            <option value="">-- اختر الجهة --</option>
                            <option value="local_unit">السيد المهندس/ رئيس الوحدة المحلية لمركز ومدينة {{ $sale->markaz }}</option>
                            <option value="agric_bank">السيد الأستاذ/ مدير عام البنك الزراعي المصري فرع {{ $sale->village }}</option>
                            <option value="agriculture">السيد المهندس/ وكيل الزراعة - مدير مديرية الزراعة بقنا</option>
                        </select>
                    </div>

                    <!-- Applicant Name -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">اسم مقدم الطلب <small class="text-muted fw-normal">(اختياري)</small></label>
                        <input type="text" name="applicant_name" class="form-control" placeholder="الاسم الثلاثي لصاحب الطلب" maxlength="200">
                    </div>

                    <!-- Purpose -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">الغرض من الإفادة <small class="text-muted fw-normal">(اختياري)</small></label>
                        <input type="text" name="purpose" class="form-control" placeholder="مثال: للتقديم لجهة حكومية" maxlength="500">
                    </div>

                    <!-- Certificate Text -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">نص الإفادة <span class="text-danger">*</span></label>
                        <div class="alert alert-info py-2 mb-2" style="font-size:.85rem">
                            <i class="bi bi-info-circle me-1"></i>يمكنك تعديل النص أدناه إذا لزم الأمر.
                        </div>
                        <textarea name="certificate_text" id="certText" class="form-control" rows="6" required style="font-size:.95rem;line-height:1.8">{{ $text }}</textarea>
                    </div>

                    <!-- Buttons -->
                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('sales.show', $sale) }}" class="btn btn-outline-secondary px-4">إلغاء</a>
                        <button type="submit" class="btn btn-primary fw-bold px-5">
                            <i class="bi bi-file-pdf me-2"></i>إصدار وطباعة الإفادة
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Preview -->
        <div class="card mt-3 border-primary border-dashed shadow-sm">
            <div class="card-header bg-light text-dark" style="font-size:.85rem">
                <i class="bi bi-eye me-1"></i>معاينة نص الإفادة
            </div>
            <div class="card-body">
                <p id="certPreview" style="font-size:.95rem;line-height:1.8;color:#1e293b;text-align:justify">{{ $text }}</p>
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