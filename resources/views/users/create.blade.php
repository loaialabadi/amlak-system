{{-- users/create.blade.php --}}
@extends('layouts.app')
@section('title', 'مستخدم جديد')
@section('page-title', 'إضافة مستخدم جديد')

@section('content')
<div class="card" style="max-width:600px;margin:0 auto">
    <div class="card-header"><i class="bi bi-person-plus-fill me-2" style="color:#c8a96e"></i>بيانات المستخدم الجديد</div>
    <div class="card-body">
        @if($errors->any())
            <div class="alert alert-danger rounded-3"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif
        <form method="POST" action="{{ route('users.store') }}">
            @csrf
            @include('users._form', ['user' => null])
            <hr>
            <div class="d-flex gap-2 justify-content-end">
                <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">إلغاء</a>
                <button class="btn fw-bold px-4" style="background:#1a3a5c;color:#fff;border-radius:8px">حفظ</button>
            </div>
        </form>
    </div>
</div>
@endsection
