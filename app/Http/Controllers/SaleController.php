<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SaleController extends Controller
{
    // ─── Index / Search ─────────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $query = Sale::with('certificates')->latest();

        if ($search = $request->input('q')) {
            $query->search($search);
        }

        if ($markaz = $request->input('markaz')) {
            $query->where('markaz', 'LIKE', "%{$markaz}%");
        }

        if ($village = $request->input('village')) {
            $query->where('village', 'LIKE', "%{$village}%");
        }

        if ($type = $request->input('type')) {
            $query->ofType($type);
        }

        if ($status = $request->input('status')) {
            $query->byStatus($status);
        }

        $sales = $query->paginate(20)->withQueryString();

        return view('sales.index', [
            'sales'    => $sales,
            'filters'  => $request->only(['q', 'markaz', 'village', 'type', 'status']),
            'types'    => Sale::$typeLabels,
            'statuses' => Sale::$statusLabels,
        ]);
    }

    // ─── Create ─────────────────────────────────────────────────────────────────

    public function create()
    {
        $this->authorizeEditor();
        return view('sales.create', [
            'types'    => Sale::$typeLabels,
            'statuses' => Sale::$statusLabels,
        ]);
    }

public function store(Request $request)
{
    $this->authorizeEditor();

    $data = $this->validateSale($request);
    $data['created_by'] = Auth::id();

    // --- الحل هنا: تحويل القيم الفارغة إلى أصفار لتجنب خطأ SQL ---
    $data['area_feddan'] = $data['area_feddan'] ?? 0;
    $data['area_qirat']  = $data['area_qirat']  ?? 0;
    $data['area_sahm']   = $data['area_sahm']   ?? 0;
    $data['area_sqm']    = $data['area_sqm']    ?? 0;
    
    // تطبيع الاسم للبحث (اختياري كما في الـ Migration)
    $data['buyer_name_normalized'] = str_replace(['أ', 'إ', 'آ'], 'ا', $data['buyer_name']);

    // Handle scan upload
    if ($request->hasFile('scan')) {
        $file = $request->file('scan');
        $path = $file->store('scans', 'public');
        $data['scan_path']          = $path;
        $data['scan_original_name'] = $file->getClientOriginalName();
    }

    $sale = Sale::create($data);

    return redirect()
        ->route('sales.show', $sale)
        ->with('success', 'تم إضافة سجل البيعة بنجاح.');
}

    // ─── Show ────────────────────────────────────────────────────────────────────

    public function show(Sale $sale)
    {
        $sale->load(['certificates.issuedBy', 'creator', 'updater']);
        return view('sales.show', compact('sale'));
    }

    // ─── Edit ────────────────────────────────────────────────────────────────────

    public function edit(Sale $sale)
    {
        $this->authorizeEditor();
        return view('sales.edit', [
            'sale'     => $sale,
            'types'    => Sale::$typeLabels,
            'statuses' => Sale::$statusLabels,
        ]);
    }

    public function update(Request $request, Sale $sale)
    {
        $this->authorizeEditor();

        $data = $this->validateSale($request, $sale->id);
        $data['updated_by'] = Auth::id();

        if ($request->hasFile('scan')) {
            // Delete old scan
            if ($sale->scan_path) {
                Storage::disk('public')->delete($sale->scan_path);
            }
            $file = $request->file('scan');
            $path = $file->store('scans', 'public');
            $data['scan_path']          = $path;
            $data['scan_original_name'] = $file->getClientOriginalName();
        }

        $sale->update($data);

        return redirect()
            ->route('sales.show', $sale)
            ->with('success', 'تم تحديث سجل البيعة بنجاح.');
    }

    // ─── Delete ─────────────────────────────────────────────────────────────────

    public function destroy(Sale $sale)
    {
        $this->authorizeAdmin();
        $sale->delete();
        return redirect()->route('sales.index')->with('success', 'تم حذف السجل.');
    }

    // ─── Validation ─────────────────────────────────────────────────────────────

    private function validateSale(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'sale_number'    => ['required', 'string', 'max:50'],
            'sale_letter'    => ['nullable', 'string', 'max:10'],
            'buyer_name'     => ['required', 'string', 'max:200'],
            'markaz'         => ['required', 'string', 'max:100'],
            'village'        => ['required', 'string', 'max:100'],
            'basin_name'     => ['nullable', 'string', 'max:100'],
            'sale_type'      => ['required', Rule::in(['agricultural', 'buildings'])],
            'area_feddan'    => ['nullable', 'integer', 'min:0'],
            'area_qirat'     => ['nullable', 'integer', 'min:0', 'max:23'],
            'area_sahm'      => ['nullable', 'integer', 'min:0', 'max:23'],
            'area_sqm'       => ['nullable', 'numeric', 'min:0'],
            'payment_status' => ['required', Rule::in(['paid', 'unpaid', 'unknown'])],
            'book_number'    => ['required', 'string', 'max:50'],
            'page_number'    => ['required', 'string', 'max:20'],
            'notes'          => ['nullable', 'string'],
            'scan'           => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf,webp', 'max:20480'],
        ], [
            'sale_number.required' => 'رقم البيعة مطلوب.',
            'buyer_name.required'  => 'اسم المشتري مطلوب.',
            'markaz.required'      => 'المركز مطلوب.',
            'village.required'     => 'القرية/الناحية مطلوبة.',
            'sale_type.required'   => 'نوع البيعة مطلوب.',
            'payment_status.required' => 'حالة السداد مطلوبة.',
            'book_number.required' => 'رقم الدفتر مطلوب.',
            'page_number.required' => 'رقم الصفحة مطلوب.',
            'scan.mimes'           => 'يجب أن يكون الملف صورة (JPG, PNG) أو PDF.',
            'scan.max'             => 'حجم الملف يجب ألا يتجاوز 20 ميجابايت.',
        ]);
    }

    // ─── Authorization helpers ───────────────────────────────────────────────────

    private function authorizeEditor(): void
    {
        if (!Auth::user()->isEditor()) {
            abort(403, 'غير مصرح لك بهذا الإجراء.');
        }
    }

    private function authorizeAdmin(): void
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'هذا الإجراء للمدير فقط.');
        }
    }
}
