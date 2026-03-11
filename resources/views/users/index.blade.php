@extends('layouts.app')
@section('title', 'إدارة المستخدمين')
@section('page-title', 'إدارة المستخدمين')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <span class="text-muted" style="font-size:.9rem">إدارة حسابات المستخدمين وصلاحياتهم</span>
    <a href="{{ route('users.create') }}" class="btn btn-sm fw-bold" style="background:#1a3a5c;color:#fff;border-radius:8px">
        <i class="bi bi-person-plus me-1"></i>مستخدم جديد
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>الاسم</th>
                    <th>البريد الإلكتروني</th>
                    <th>الدور</th>
                    <th>الحالة</th>
                    <th>تاريخ الإنشاء</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr class="{{ $user->trashed() ? 'table-secondary text-muted' : '' }}">
                    <td>{{ $user->id }}</td>
                    <td>
                        {{ $user->name }}
                        @if($user->id === auth()->id())
                            <span class="badge" style="background:#c8a96e;color:#1a3a5c;font-size:.7rem">أنت</span>
                        @endif
                    </td>
                    <td style="font-size:.85rem">{{ $user->email }}</td>
                    <td>
                        <span class="badge" style="background:{{ $user->role === 'admin' ? '#1a3a5c' : ($user->role === 'editor' ? '#059669' : '#64748b') }};font-size:.78rem">
                            {{ $user->role_label }}
                        </span>
                    </td>
                    <td>
                        @if($user->trashed())
                            <span class="badge badge-unpaid">محذوف</span>
                        @elseif($user->is_active)
                            <span class="badge badge-paid">نشط</span>
                        @else
                            <span class="badge badge-unknown">معطل</span>
                        @endif
                    </td>
                    <td style="font-size:.8rem;color:#64748b">{{ $user->created_at->format('Y/m/d') }}</td>
                    <td>
                        @if(!$user->trashed())
                        <div class="d-flex gap-1">
                            <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @if($user->id !== auth()->id())
                            <form method="POST" action="{{ route('users.destroy', $user) }}"
                                  onsubmit="return confirm('هل تريد تعطيل هذا المستخدم؟')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                            @endif
                        </div>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-3">{{ $users->links('pagination::bootstrap-5') }}</div>
    </div>
</div>

@endsection
