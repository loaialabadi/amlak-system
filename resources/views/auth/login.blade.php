<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - نظام أملاك الدولة</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Cairo', sans-serif; }
        body {
            background: linear-gradient(135deg, #1a3a5c 0%, #0d2137 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: #fff;
            border-radius: 20px;
            padding: 2.5rem 2rem;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 20px 60px rgba(0,0,0,.3);
        }
        .login-logo {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .login-logo .icon { font-size: 3.5rem; }
        .login-logo h4 {
            color: #1a3a5c;
            font-weight: 700;
            margin: .5rem 0 .2rem;
        }
        .login-logo p { color: #64748b; font-size: .85rem; }
        .form-control {
            border-radius: 10px;
            padding: .65rem 1rem;
            border-color: #e2e8f0;
        }
        .form-control:focus { border-color: #1a3a5c; box-shadow: 0 0 0 3px rgba(26,58,92,.15); }
        .btn-login {
            background: #1a3a5c;
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: .7rem;
            font-weight: 700;
            font-size: 1rem;
            width: 100%;
            transition: background .2s;
        }
        .btn-login:hover { background: #0d2137; color: #fff; }
        label { font-weight: 600; color: #374151; font-size: .88rem; }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-logo">
            <div class="icon">🏛️</div>
            <h4>نظام أملاك الدولة</h4>
            <p>إدارة وأرشفة سجلات البيوع</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger rounded-3 py-2 mb-3 text-center" style="font-size:.88rem;">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <label>البريد الإلكتروني</label>
                <input type="email" name="email" class="form-control mt-1"
                       value="{{ old('email') }}" required autofocus
                       placeholder="example@gov.eg">
            </div>
            <div class="mb-4">
                <label>كلمة المرور</label>
                <input type="password" name="password" class="form-control mt-1" required>
            </div>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label" for="remember" style="font-size:.85rem;">تذكرني</label>
            </div>
            <button type="submit" class="btn-login">
                دخول &larr;
            </button>
        </form>

        <div class="text-center mt-3" style="color:#94a3b8;font-size:.78rem;">
            نظام مخصص للاستخدام الرسمي فقط
        </div>
    </div>
</body>
</html>
