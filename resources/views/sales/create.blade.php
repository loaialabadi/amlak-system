@extends('layouts.app')

@section('title', 'إضافة سجل بيعة جديد')
@section('page-title', 'إضافة سجل بيعة جديد')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('sales.index') }}" style="color:#1a3a5c">السجلات</a></li>
            <li class="breadcrumb-item active">إضافة جديد</li>
        </ol>
    </nav>
</div>

<div class="card">
    <div class="card-header">
        <i class="bi bi-plus-circle-fill me-2" style="color:#c8a96e"></i>
        بيانات البيعة الجديدة
    </div>
    <div class="card-body">
        @if($errors->any())
            <div class="alert alert-danger rounded-3 mb-3">
                <strong>يرجى تصحيح الأخطاء التالية:</strong>
                <ul class="mb-0 mt-1">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('sales.store') }}" enctype="multipart/form-data">
            @csrf
            @include('sales._form')

            <hr class="my-4">
            <div class="d-flex gap-2 justify-content-end">
                <a href="{{ route('sales.index') }}" class="btn btn-outline-secondary px-4">إلغاء</a>
                <button type="submit" class="btn px-5 fw-bold" style="background:#1a3a5c;color:#fff;border-radius:8px;">
                    <i class="bi bi-save me-2"></i>حفظ السجل
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
