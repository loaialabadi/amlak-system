<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

<title>إفادة رقم {{ $certificate->certificate_number }}</title>

<style>

@font-face {
    font-family: "DejaVu Sans";
    src: url("{{ storage_path('fonts/DejaVuSans.ttf') }}") format("truetype");
}

body {
    direction: rtl;
    text-align: right;
    font-family: "DejaVu Sans", sans-serif;
    background: #fff;
    color: #111;
    font-size: 13pt;
    line-height: 2;
}

        /* ── Page layout ── */
        .page {
            width: 210mm;
            min-height: 297mm;
            padding: 20mm 22mm;
            display: flex;
            flex-direction: column;
        }

        /* ── Header ── */
        .header {
            text-align: center;
            border-bottom: 3px double #1a3a5c;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }

        .header .gov-title {
            font-size: 14pt;
            font-weight: bold;
            color: #1a3a5c;
            letter-spacing: 1px;
        }

        .header .dept-title {
            font-size: 12pt;
            color: #374151;
            margin-top: 3px;
        }

        .header .sub-title {
            font-size: 11pt;
            color: #64748b;
            margin-top: 2px;
        }

        /* ── Certificate title ── */
        .cert-title-box {
            border: 2px solid #1a3a5c;
            border-radius: 4px;
            text-align: center;
            padding: 8px;
            margin: 20px auto;
            width: 200px;
            background: #f0f4f8;
        }

        .cert-title-box h2 {
            font-size: 16pt;
            font-weight: bold;
            color: #1a3a5c;
            margin: 0;
        }

        /* ── Cert number & date row ── */
        .cert-meta {
            display: table;
            width: 100%;
            margin-bottom: 18px;
            font-size: 11pt;
        }

        .cert-meta .left,
        .cert-meta .right {
            display: table-cell;
            width: 50%;
            padding: 4px 0;
        }

        .cert-meta .right { text-align: right; }
        .cert-meta .left  { text-align: left; }

        .cert-meta span {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 2px 10px;
            border-radius: 4px;
            font-size: 11pt;
        }

        /* ── Divider ── */
        .divider {
            border: none;
            border-top: 1px solid #e2e8f0;
            margin: 15px 0;
        }

        /* ── Body text ── */
        .body-text {
            font-size: 13pt;
            line-height: 2.4;
            text-align: justify;
            color: #111;
            padding: 0 5px;
        }

        /* ── Status highlight ── */
        .status-paid    { color: #065f46; font-weight: bold; }
        .status-unpaid  { color: #991b1b; font-weight: bold; }
        .status-unknown { color: #92400e; font-weight: bold; }

        /* ── Footer section ── */
        .footer-section {
            margin-top: 40px;
            border-top: 1px solid #e2e8f0;
            padding-top: 15px;
        }

        .sig-block {
            display: table;
            width: 100%;
            margin-top: 50px;
        }

        .sig-cell {
            display: table-cell;
            width: 50%;
            text-align: center;
            font-size: 11pt;
            color: #374151;
        }

        .sig-line {
            border-bottom: 1px solid #374151;
            width: 160px;
            margin: 60px auto 5px;
        }

        /* ── Watermark ── */
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            font-size: 70pt;
            color: rgba(26,58,92,.04);
            white-space: nowrap;
            font-weight: bold;
            z-index: -1;
        }

        /* ── Footer stamp bar ── */
        .stamp-bar {
            position: fixed;
            bottom: 15mm;
            left: 22mm;
            right: 22mm;
            border-top: 2px solid #1a3a5c;
            padding-top: 6px;
            font-size: 9pt;
            color: #64748b;
            display: flex;
            justify-content: space-between;
        }

        /* ── Applicant box ── */
        .applicant-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 10px 15px;
            margin-bottom: 20px;
            font-size: 11pt;
        }
    </style>
</head>
<body>

<div class="watermark">أملاك الدولة</div>

<div class="page">

    {{-- ── Header ── --}}
    <div class="header">
        <div class="gov-title">جمهورية مصر العربية</div>
        <div class="dept-title">وزارة المالية &mdash; مصلحة الأملاك الأميرية</div>
        <div class="sub-title">إدارة أملاك الدولة</div>
    </div>

    {{-- ── Certificate title ── --}}
    <div class="cert-title-box">
        <h2>إفـادة رسمية</h2>
    </div>

    {{-- ── Meta row ── --}}
    <div class="cert-meta">
        <div class="right">
            رقم الإفادة: <span>{{ $certificate->certificate_number }}</span>
        </div>
        <div class="left">
            التاريخ: <span>{{ $certificate->issued_at->format('Y/m/d') }}</span>
        </div>
    </div>

    <hr class="divider">

    {{-- ── Applicant ── --}}
    @if($certificate->applicant_name || $certificate->purpose)
    <div class="applicant-box">
        @if($certificate->applicant_name)
            <strong>مقدم الطلب:</strong> {{ $certificate->applicant_name }}&nbsp;&nbsp;&nbsp;
        @endif
        @if($certificate->purpose)
            <strong>الغرض:</strong> {{ $certificate->purpose }}
        @endif
    </div>
    @endif

    {{-- ── Body text ── --}}
    <div class="body-text">
        <p style="text-indent:40px">
            بناءً على ما هو مقيد بسجلات أملاك الدولة ودفاترها الرسمية، وبعد الاطلاع والمراجعة الدقيقة،
        </p>
        <p style="text-indent:40px; margin-top:10px">
            {{ $certificate->certificate_text }}
        </p>
        <p style="text-indent:40px; margin-top:10px">
            وقد صدرت هذه الإفادة بناءً على الطلب المقدم وذلك للاستفادة منها فيما يُرام.
        </p>
    </div>

    <hr class="divider" style="margin-top:30px">

    {{-- ── Signature ── --}}
    <div class="sig-block">
        <div class="sig-cell">
            <div>مأمور التسجيل</div>
            <div class="sig-line"></div>
            <div>{{ $certificate->issuedBy->name }}</div>
        </div>
        <div class="sig-cell">
            <div>رئيس قسم أملاك الدولة</div>
            <div class="sig-line"></div>
            <div>التوقيع والختم الرسمي</div>
        </div>
    </div>

    {{-- ── Ref ── --}}
    <div style="text-align:center;margin-top:25px;font-size:10pt;color:#94a3b8">
        مرجع البيعة: دفتر رقم {{ $certificate->sale->book_number }} &mdash; صفحة {{ $certificate->sale->page_number }}
    </div>

</div>

{{-- Fixed footer stamp --}}
<div class="stamp-bar">
    <span>رقم الإفادة: {{ $certificate->certificate_number }}</span>
    <span>نظام إدارة أملاك الدولة الرقمي</span>
    <span>{{ $certificate->issued_at->format('Y/m/d H:i') }}</span>
</div>

</body>
</html>
