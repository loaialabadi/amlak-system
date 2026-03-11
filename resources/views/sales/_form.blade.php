{{--
  Shared form partial for create and edit sale pages.
  Requires: $types, $statuses, $sale (optional for edit)
--}}

<div class="row g-3">
    {{-- ── Section: بيانات البيعة ── --}}
    <div class="col-12">
        <div class="p-3 rounded-3 mb-1" style="background:#f8fafc;border-right:4px solid #1a3a5c">
            <h6 class="mb-0 fw-bold" style="color:#1a3a5c"><i class="bi bi-file-earmark-text me-2"></i>بيانات البيعة</h6>
        </div>
    </div>

    <div class="col-md-3">
        <label class="form-label fw-600">الحرف <small class="text-muted fw-normal">(اختياري)</small></label>
        <input type="text" name="sale_letter" class="form-control @error('sale_letter') is-invalid @enderror"
               value="{{ old('sale_letter', $sale->sale_letter ?? '') }}"
               placeholder="مثال: أ، ب" maxlength="10">
        @error('sale_letter')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label class="form-label fw-600">رقم البيعة <span class="text-danger">*</span></label>
        <input type="text" name="sale_number" class="form-control @error('sale_number') is-invalid @enderror"
               value="{{ old('sale_number', $sale->sale_number ?? '') }}"
               placeholder="مثال: 1234" required maxlength="50">
        @error('sale_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-5">
        <label class="form-label fw-600">اسم المشتري <span class="text-danger">*</span></label>
        <input type="text" name="buyer_name" class="form-control @error('buyer_name') is-invalid @enderror"
               value="{{ old('buyer_name', $sale->buyer_name ?? '') }}"
               placeholder="الاسم الثلاثي" required maxlength="200">
        @error('buyer_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- ── Section: الموقع ── --}}
    <div class="col-12 mt-2">
        <div class="p-3 rounded-3 mb-1" style="background:#f8fafc;border-right:4px solid #c8a96e">
            <h6 class="mb-0 fw-bold" style="color:#1a3a5c"><i class="bi bi-geo-alt me-2"></i>الموقع الجغرافي</h6>
        </div>
    </div>

    <div class="col-md-6">
        <label class="form-label fw-600">المركز <span class="text-danger">*</span></label>
        <input type="text" name="markaz" class="form-control @error('markaz') is-invalid @enderror"
               value="{{ old('markaz', $sale->markaz ?? '') }}"
               placeholder="مثال: قنا" required maxlength="100">
        @error('markaz')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-600">الناحية / القرية <span class="text-danger">*</span></label>
        <input type="text" name="village" class="form-control @error('village') is-invalid @enderror"
               value="{{ old('village', $sale->village ?? '') }}"
               placeholder="مثال: الكرنك" required maxlength="100">
        @error('village')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- ── Section: النوع والمساحة ── --}}
    <div class="col-12 mt-2">
        <div class="p-3 rounded-3 mb-1" style="background:#f8fafc;border-right:4px solid #059669">
            <h6 class="mb-0 fw-bold" style="color:#1a3a5c"><i class="bi bi-rulers me-2"></i>نوع البيعة والمساحة</h6>
        </div>
    </div>

    <div class="col-md-4">
        <label class="form-label fw-600">نوع البيعة <span class="text-danger">*</span></label>
        <select name="sale_type" id="sale_type" class="form-select @error('sale_type') is-invalid @enderror" required>
            <option value="">-- اختر --</option>
            @foreach($types as $key => $label)
                <option value="{{ $key }}" {{ old('sale_type', $sale->sale_type ?? '') == $key ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        @error('sale_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- Agricultural area --}}
    <div id="section-agricultural" class="col-md-8 row g-2" style="display:none!important">
        <div class="col-md-4">
            <label class="form-label fw-600">فدان</label>
            <input type="number" name="area_feddan" class="form-control"
                   value="{{ old('area_feddan', $sale->area_feddan ?? 0) }}" min="0">
        </div>
        <div class="col-md-4">
            <label class="form-label fw-600">قيراط <small class="text-muted">(0-23)</small></label>
            <input type="number" name="area_qirat" class="form-control"
                   value="{{ old('area_qirat', $sale->area_qirat ?? 0) }}" min="0" max="23">
        </div>
        <div class="col-md-4">
            <label class="form-label fw-600">سهم <small class="text-muted">(0-23)</small></label>
            <input type="number" name="area_sahm" class="form-control"
                   value="{{ old('area_sahm', $sale->area_sahm ?? 0) }}" min="0" max="23">
        </div>
        <div class="col-12">
            <label class="form-label fw-600">اسم الحوض <small class="text-muted">(اختياري)</small></label>
            <input type="text" name="basin_name" class="form-control"
                   value="{{ old('basin_name', $sale->basin_name ?? '') }}" maxlength="100"
                   placeholder="مثال: حوض الجبل">
        </div>
    </div>

    {{-- Buildings area --}}
    <div id="section-buildings" class="col-md-8" style="display:none!important">
        <label class="form-label fw-600">المساحة (متر مربع)</label>
        <div class="input-group">
            <input type="number" name="area_sqm" class="form-control"
                   value="{{ old('area_sqm', $sale->area_sqm ?? '') }}" min="0" step="0.01"
                   placeholder="0.00">
            <span class="input-group-text">م²</span>
        </div>
    </div>

    {{-- ── Section: السداد والمرجع ── --}}
    <div class="col-12 mt-2">
        <div class="p-3 rounded-3 mb-1" style="background:#f8fafc;border-right:4px solid #dc2626">
            <h6 class="mb-0 fw-bold" style="color:#1a3a5c"><i class="bi bi-book me-2"></i>حالة السداد ومرجع الدفتر</h6>
        </div>
    </div>

    <div class="col-md-4">
        <label class="form-label fw-600">حالة السداد <span class="text-danger">*</span></label>
        <select name="payment_status" class="form-select @error('payment_status') is-invalid @enderror" required>
            @foreach($statuses as $key => $label)
                <option value="{{ $key }}" {{ old('payment_status', $sale->payment_status ?? 'unknown') == $key ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        @error('payment_status')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label class="form-label fw-600">رقم الدفتر <span class="text-danger">*</span></label>
        <input type="text" name="book_number" class="form-control @error('book_number') is-invalid @enderror"
               value="{{ old('book_number', $sale->book_number ?? '') }}"
               placeholder="مثال: 5" required maxlength="50">
        @error('book_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label class="form-label fw-600">رقم الصفحة <span class="text-danger">*</span></label>
        <input type="text" name="page_number" class="form-control @error('page_number') is-invalid @enderror"
               value="{{ old('page_number', $sale->page_number ?? '') }}"
               placeholder="مثال: 47" required maxlength="20">
        @error('page_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- ── Section: الأرشفة والملاحظات ── --}}
    <div class="col-12 mt-2">
        <div class="p-3 rounded-3 mb-1" style="background:#f8fafc;border-right:4px solid #7c3aed">
            <h6 class="mb-0 fw-bold" style="color:#1a3a5c"><i class="bi bi-image me-2"></i>الأرشفة الرقمية والملاحظات</h6>
        </div>
    </div>

    <div class="col-md-6">
        <label class="form-label fw-600">
            صورة الصفحة (Scan)
            <small class="text-muted fw-normal">JPG, PNG, PDF — حتى 20 ميجا</small>
        </label>

        @if(isset($sale) && $sale->scan_path)
            <div class="mb-2">
                <a href="{{ $sale->scan_url }}" target="_blank" class="btn btn-sm btn-outline-success">
                    <i class="bi bi-eye me-1"></i>عرض الصورة الحالية
                </a>
                <small class="text-muted ms-2">{{ $sale->scan_original_name }}</small>
            </div>
        @endif

        <input type="file" name="scan" class="form-control @error('scan') is-invalid @enderror"
               accept=".jpg,.jpeg,.png,.pdf,.webp" id="scanInput">
        <div id="scanPreviewContainer" class="mt-2" style="display:none">
            <img id="scanPreview" class="img-thumbnail" style="max-height:140px">
        </div>
        @error('scan')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-600">ملاحظات</label>
        <textarea name="notes" class="form-control" rows="4"
                  placeholder="أي معلومات إضافية...">{{ old('notes', $sale->notes ?? '') }}</textarea>
    </div>
</div>

@push('scripts')
<script>
    const saleTypeSelect = document.getElementById('sale_type');
    const secAg = document.getElementById('section-agricultural');
    const secBu = document.getElementById('section-buildings');

    function toggleAreaSections() {
        const v = saleTypeSelect.value;
        secAg.style.setProperty('display', v === 'agricultural' ? 'flex' : 'none', 'important');
        secBu.style.setProperty('display', v === 'buildings'    ? 'block' : 'none', 'important');
    }

    saleTypeSelect.addEventListener('change', toggleAreaSections);
    toggleAreaSections(); // on load

    // Image preview
    document.getElementById('scanInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = ev => {
                document.getElementById('scanPreview').src = ev.target.result;
                document.getElementById('scanPreviewContainer').style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            document.getElementById('scanPreviewContainer').style.display = 'none';
        }
    });
</script>
@endpush
