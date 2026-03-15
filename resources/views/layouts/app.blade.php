<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'نظام أملاك الدولة')</title>
<!-- 
    {{-- Bootstrap RTL --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css">
    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    {{-- Google Fonts Arabic --}}
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet"> -->


    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.rtl.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/icons/bootstrap-icons.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    <style>
        :root {
            --primary:   #1a3a5c;
            --accent:    #c8a96e;
            --sidebar-w: 260px;
        }

        * { font-family: 'Cairo', sans-serif; }

        body {
            background: #f4f6f9;
            min-height: 100vh;
        }

        /* ── Sidebar ── */
        #sidebar {
            width: var(--sidebar-w);
            min-height: 100vh;
            background: var(--primary);
            position: fixed;
            top: 0; right: 0;
            z-index: 1040;
            transition: transform .3s;
            display: flex;
            flex-direction: column;
        }

        .sidebar-brand {
            padding: 1.5rem 1rem;
            background: rgba(0,0,0,.2);
            text-align: center;
        }
        .sidebar-brand img { width: 52px; }
        .sidebar-brand h6 {
            color: var(--accent);
            font-weight: 700;
            font-size: .85rem;
            margin: .4rem 0 0;
            line-height: 1.4;
        }

        .sidebar-nav { flex: 1; padding: 1rem 0; }

        .nav-section-label {
            color: rgba(255,255,255,.4);
            font-size: .7rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: .6rem 1.2rem .2rem;
        }

        .nav-link-item {
            display: flex;
            align-items: center;
            gap: .6rem;
            padding: .6rem 1.4rem;
            color: rgba(255,255,255,.75);
            text-decoration: none;
            font-size: .9rem;
            border-right: 3px solid transparent;
            transition: all .2s;
        }
        .nav-link-item:hover,
        .nav-link-item.active {
            color: #fff;
            background: rgba(255,255,255,.08);
            border-right-color: var(--accent);
        }
        .nav-link-item i { font-size: 1.1rem; width: 1.2rem; text-align: center; }

        /* ── Main ── */
        #main-content {
            margin-right: var(--sidebar-w);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .topbar {
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            padding: .75rem 1.5rem;
            position: sticky;
            top: 0;
            z-index: 900;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .topbar-title { font-size: 1.05rem; font-weight: 700; color: var(--primary); }
        .topbar-date { font-size: .8rem; color: #64748b; }

        .page-body { padding: 1.5rem; flex: 1; }

        /* ── Cards ── */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 1px 4px rgba(0,0,0,.06);
        }
        .card-header {
            background: #fff;
            border-bottom: 1px solid #f1f5f9;
            border-radius: 12px 12px 0 0 !important;
            padding: 1rem 1.25rem;
            font-weight: 700;
            color: var(--primary);
        }

        /* ── Stat cards ── */
        .stat-card {
            border-radius: 12px;
            padding: 1.25rem;
            color: #fff;
            position: relative;
            overflow: hidden;
        }
        .stat-card::after {
            content: '';
            position: absolute;
            width: 80px; height: 80px;
            border-radius: 50%;
            background: rgba(255,255,255,.1);
            bottom: -20px; left: -20px;
        }
        .stat-card .stat-icon { font-size: 2rem; opacity: .8; }
        .stat-card .stat-num  { font-size: 2rem; font-weight: 700; }
        .stat-card .stat-lbl  { font-size: .82rem; opacity: .9; }

        /* ── Status badges ── */
        .badge-paid    { background: #d1fae5; color: #065f46; }
        .badge-unpaid  { background: #fee2e2; color: #991b1b; }
        .badge-unknown { background: #fef3c7; color: #92400e; }

        /* ── Table ── */
        .table-hover tbody tr:hover { background: #f8fafc; cursor: pointer; }
        .table th { font-size: .82rem; color: #64748b; font-weight: 600; white-space: nowrap; }

        /* ── Search bar ── */
        .search-card { background: var(--primary); border-radius: 14px; padding: 1.5rem; }
        .search-card .form-control,
        .search-card .form-select {
            border: none;
            border-radius: 8px;
            font-size: .9rem;
        }

        /* ── Scan preview ── */
        .scan-preview {
            max-width: 100%;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            cursor: zoom-in;
        }

        /* ── Responsive ── */
        @media (max-width: 767px) {
            #sidebar { transform: translateX(100%); }
            #sidebar.open { transform: translateX(0); }
            #main-content { margin-right: 0; }
        }
    </style>

    @stack('styles')
</head>
<body>

{{-- ════ SIDEBAR ════ --}}
<nav id="sidebar">
    <div class="sidebar-brand">
        <div style="font-size:2.5rem;">🏛️</div>
        <h6>نظام أملاك الدولة<br><small style="color:rgba(255,255,255,.5);font-weight:400;">إدارة سجلات البيوع</small></h6>
    </div>

    <i class="bi bi-person"></i>
<i class="bi bi-house"></i>
<i class="bi bi-printer"></i>


    <div class="sidebar-nav">
        <div class="nav-section-label">الرئيسية</div>
        <a href="{{ route('dashboard') }}" class="nav-link-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> لوحة التحكم
        </a>

        <div class="nav-section-label">البيوع</div>
        <a href="{{ route('sales.index') }}" class="nav-link-item {{ request()->routeIs('sales.index') ? 'active' : '' }}">
            <i class="bi bi-search"></i> البحث في السجلات
        </a>
        @if(auth()->user()->isEditor())
        <a href="{{ route('sales.create') }}" class="nav-link-item {{ request()->routeIs('sales.create') ? 'active' : '' }}">
            <i class="bi bi-plus-circle"></i> إضافة سجل جديد
        </a>
        @endif

        @if(auth()->user()->isAdmin())
        <div class="nav-section-label">الإدارة</div>
        <a href="{{ route('users.index') }}" class="nav-link-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
            <i class="bi bi-people"></i> إدارة المستخدمين
        </a>
        @endif
    </div>

    <div class="p-3 border-top border-white border-opacity-10">
        <div class="d-flex align-items-center gap-2 mb-2">
            <div class="bg-white bg-opacity-20 rounded-circle d-flex align-items-center justify-content-center" style="width:36px;height:36px;">
                <i class="bi bi-person text-white"></i>
            </div>
            <div>
                <div style="color:#fff;font-size:.85rem;font-weight:600;">{{ auth()->user()->name }}</div>
                <div style="color:rgba(255,255,255,.5);font-size:.72rem;">{{ auth()->user()->role_label }}</div>
            </div>
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-sm w-100" style="background:rgba(255,255,255,.1);color:#fff;font-size:.8rem;">
                <i class="bi bi-box-arrow-left me-1"></i> تسجيل الخروج
            </button>
        </form>
    </div>
</nav>

{{-- ════ MAIN ════ --}}
<div id="main-content">
    <div class="topbar">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-sm d-md-none" onclick="document.getElementById('sidebar').classList.toggle('open')">
                <i class="bi bi-list fs-5"></i>
            </button>
            <span class="topbar-title">@yield('page-title', 'لوحة التحكم')</span>
        </div>
        <span class="topbar-date">
            <i class="bi bi-calendar3 me-1"></i>
            {{ \Carbon\Carbon::now()->locale('ar')->isoFormat('dddd، D MMMM YYYY') }}
        </span>
    </div>

    <div class="page-body">
        {{-- Flash messages --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 mb-3" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-3" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
