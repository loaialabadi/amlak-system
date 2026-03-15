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

/* إعداد صفحة الطباعة */
@page {
    size: A4;
    margin: 18mm 20mm;
}

body{
    direction: rtl;
    text-align: right;
    font-family: "DejaVu Sans", sans-serif;
    background:#fff;
    color:#111;
    font-size:13pt;
    line-height:1.9;
    margin:0;
}

/* الصفحة */
.page{
    width:100%;
    max-width:210mm;
    margin:auto;
}

/* Header */

.header{
    text-align:center;
    border-bottom:3px double #1a3a5c;
    padding-bottom:10px;
    margin-bottom:15px;
}

.gov-title{
    font-size:14pt;
    font-weight:bold;
    color:#1a3a5c;
}

.sub-title{
    font-size:11pt;
    color:#475569;
}

/* عنوان الافادة */

.cert-title-box{
    border:2px solid #1a3a5c;
    text-align:center;
    padding:6px;
    margin:15px auto;
    width:180px;
    background:#f1f5f9;
}

.cert-title-box h2{
    margin:0;
    font-size:15pt;
    color:#1a3a5c;
}

/* بيانات الإفادة */

.cert-meta{
    width:100%;
    font-size:11pt;
    margin-bottom:15px;
}

.cert-meta table{
    width:100%;
}

.cert-meta td{
    padding:4px 0;
}

.meta-box{
    background:#f8fafc;
    border:1px solid #e2e8f0;
    padding:2px 8px;
}

/* divider */

.divider{
    border:none;
    border-top:1px solid #e2e8f0;
    margin:12px 0;
}

/* مقدم الطلب */

.applicant-box{
    background:#f8fafc;
    border:1px solid #e2e8f0;
    padding:8px 12px;
    margin-bottom:15px;
    font-size:11pt;
}

/* النص */

.body-text{
    font-size:13pt;
    line-height:2.2;
    text-align:justify;
}

.body-text p{
    text-indent:35px;
    margin:8px 0;
}

/* التوقيعات */

.sig-block{
    width:100%;
    margin-top:40px;
}

.sig-block table{
    width:100%;
    text-align:center;
}

.sig-line{
    border-bottom:1px solid #000;
    width:150px;
    margin:45px auto 5px;
}

/* العلامة المائية */

.watermark{
    position:fixed;
    top:45%;
    left:50%;
    transform:translate(-50%,-50%) rotate(-30deg);
    font-size:70pt;
    color:rgba(0,0,0,0.05);
    z-index:-1;
}

/* الفوتر */

.stamp-bar{
    position:fixed;
    bottom:10mm;
    left:20mm;
    right:20mm;
    border-top:1px solid #1a3a5c;
    padding-top:4px;
    font-size:9pt;
    color:#64748b;
}

.stamp-bar table{
    width:100%;
}

.ref{
    text-align:center;
    margin-top:20px;
    font-size:10pt;
    color:#94a3b8;
}

</style>
</head>
<body>

<div class="watermark">أملاك الدولة</div>

<div class="page">

    {{-- ── Header ── --}}
    <div class="header">
        <div class="gov-title">محافظه قنا  </div>
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

<p><strong>إلى:</strong> {{ $certificate->recipient == 'local_unit' ? "السيد المهندس/ رئيس الوحدة المحلية لمركز ومدينة {$certificate->sale->markaz}" : ($certificate->recipient == 'agric_bank' ? "السيد الأستاذ/ مدير عام البنك الزراعي المصري فرع {$certificate->sale->village}" : "السيد المهندس/ وكيل الزراعة - مدير مديرية الزراعة بقنا") }}</p>

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

                <p style="text-indent:40px; margin-top:10px">
      وتفضلوا بقبول فائق الاحترام
        </p>
    </div>

    <hr class="divider" style="margin-top:30px">

    {{-- ── Signature ── --}}
<div class="sig-block">
<table>
<tr>

<td>
<div>المختص</div>
<div class="sig-line"></div>
<div>{{ $certificate->issuedBy->name }}</div>
</td>

<td>
<div>رئيس القسم</div>
<div class="sig-line"></div>
</td>

<td>
<div>مدير إدارة أملاك قنا</div>
<div class="sig-line"></div>
<div>التوقيع والختم الرسمي</div>
<div>م/محمد احمد عبدالحميد</div>
</td>

</tr>
</table>
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
