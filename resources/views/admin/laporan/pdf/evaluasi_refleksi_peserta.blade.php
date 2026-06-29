<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Lembar Evaluasi dan Refleksi Harian Peserta</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.5;
            color: #000;
            margin: 0;
            padding: 10px;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
        }
        .header h1 {
            font-size: 14pt;
            margin: 0;
            text-transform: uppercase;
            font-weight: bold;
        }
        .header h2 {
            font-size: 12pt;
            margin: 4px 0 0 0;
            text-transform: uppercase;
            font-weight: bold;
        }
        .header h3 {
            font-size: 11pt;
            margin: 4px 0 0 0;
            text-transform: uppercase;
            font-weight: normal;
        }
        .meta-table {
            width: 100%;
            margin-bottom: 25px;
            font-weight: bold;
            border-bottom: 2px solid #000;
            padding-bottom: 5px;
        }
        .question-block {
            margin-bottom: 20px;
        }
        .question-text {
            font-weight: bold;
            margin-bottom: 5px;
            text-align: justify;
        }
        .answer-box {
            border: 1px solid #ccc;
            padding: 10px;
            background-color: #f9f9f9;
            min-height: 50px;
            font-style: italic;
            border-radius: 4px;
        }
        .signature-table {
            width: 100%;
            margin-top: 40px;
        }
        @page {
            margin: 40px 40px 60px 40px;
        }
        .footer-motto {
            position: fixed;
            bottom: 0;
            left: 10px;
            right: 40px;
            height: 20px;
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            color: #22c55e;
            font-weight: bold;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="footer-motto">Belajar, Berjuang, Bertaqwa</div>
    @php
        $pages = isset($is_all) && $is_all ? $reportData : collect([['peserta' => $peserta, 'hari_ke' => $hari_ke, 'evaluasi' => $evaluasi]]);
    @endphp
    @foreach($pages as $page)
        @php
            $peserta = $page['peserta'];
            $hari_ke = $page['hari_ke'];
            $evaluasi = $page['evaluasi'];
        @endphp
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
            <tr>
                <td style="width: 20%; text-align: left; vertical-align: middle; padding-bottom: 8px;">
                    <img src="{{ public_path('logo.png') }}" style="height: 160px; width: auto;">
                </td>
                <td style="width: 80%; text-align: right; vertical-align: middle; padding-bottom: 8px; padding-right: 5px;">
                    <div style="font-family: 'Times New Roman', Times, serif; font-size: 16pt; color: #00A651; font-weight: bold; line-height: 1.25; text-transform: uppercase;">
                        PANITIA PELAKSANA LATIHAN KADER MUDA V<br>
                        PIMPINAN ANAK CABANG<br>
                        IKATAN PELAJAR NAHDLATUL ULAMA<br>
                        IKATAN PELAJAR PUTRI NAHDLATUL ULAMA<br>
                        KECAMATAN KAUMAN
                    </div>
                    <div style="font-family: Arial, Helvetica, sans-serif; font-size: 9pt; color: #000000; font-weight: bold; line-height: 1.35; margin-top: 5px;">
                        Jln. Sidoluhur Gg. II, Dsn. Bancaan, Ds. Mojosari, Kec. Kauman - Tulungagung<br>
                        0883011340460/089617377022<br>
                        pacipippkauman@gmail.com<br>
                        pacipnuippnukauman.online
                    </div>
                </td>
            </tr>
        </table>

        <div class="header" style="text-align: center; margin-top: 15px; margin-bottom: 15px;">
            <h1 style="font-size: 12pt; font-weight: bold; text-transform: uppercase;">LEMBAR EVALUASI & REFLEKSI HARIAN</h1>
            <h2 style="font-size: 11pt; font-weight: bold; text-transform: uppercase; margin-top: 3px; color: #444;">PESERTA LATIHAN KADER MUDA</h2>
        </div>

        <table class="meta-table">
            <tr>
                <td style="width: 50%;">Nama: {{ $peserta->name }}</td>
                <td style="width: 50%; text-align: right;">Hari Ke: {{ $hari_ke }} ({{ $evaluasi ? \Carbon\Carbon::parse($evaluasi->tanggal)->translatedFormat('d F Y') : '-' }})</td>
            </tr>
        </table>

        <div class="question-block">
            <div class="question-text">1. Pengalaman belajar apa yang rekan/rekanita dapat dari pelatihan hari ini, yang paling bermanfaat bagi perkembangan diri anda ?</div>
            <div class="answer-box">
                {!! nl2br(e($evaluasi ? $evaluasi->q1_pengalaman : 'Belum diisi')) !!}
            </div>
        </div>

        <div class="question-block">
            <div class="question-text">2. Menurut rekan/rekanita, bagaimana tingkat partisipasi anda dalam pelatihan hari ini ?</div>
            <div class="answer-box">
                {!! nl2br(e($evaluasi ? $evaluasi->q2_partisipasi : 'Belum diisi')) !!}
            </div>
        </div>

        <div class="question-block">
            <div class="question-text">3. Adakah hal yang menghambat atau mendorong rekan/rekanita untuk berpartisipasi dalam latihan hari ini ?</div>
            <div class="answer-box">
                {!! nl2br(e($evaluasi ? $evaluasi->q3_hambatan_dorongan : 'Belum diisi')) !!}
            </div>
        </div>

        <div class="question-block">
            <div class="question-text">4. Adakah rekan/rekanita dalam sesi hari ini mempunyai kesempatan untuk mengemukakan pendapat, ide pikiran. Kapan dan dalam kesempatan apa ?</div>
            <div class="answer-box">
                {!! nl2br(e($evaluasi ? $evaluasi->q4_kesempatan_pendapat : 'Belum diisi')) !!}
            </div>
        </div>

        <div class="question-block">
            <div class="question-text">5. Pengetahuan apa saja kah yang rekan/rekanita dapatkan pada hari ini ?</div>
            <div class="answer-box">
                {!! nl2br(e($evaluasi ? $evaluasi->q5_pengetahuan_didapat : 'Belum diisi')) !!}
            </div>
        </div>

        <div class="question-block">
            <div class="question-text">6. Hal apa saja kah yang menghambat rekan/rekanita dalam mengikuti latihan hari ini, terutama yang bersumber dalam diri anda sendiri ?</div>
            <div class="answer-box">
                {!! nl2br(e($evaluasi ? $evaluasi->q6_hambatan_diri_sendiri : 'Belum diisi')) !!}
            </div>
        </div>

        <table class="signature-table">
            <tr>
                <td style="width: 60%;"></td>
                <td style="text-align: right; padding-right: 40px;">
                    <div>Tulungagung, {{ now()->translatedFormat('d F Y') }}</div>
                    <div style="margin-top: 5px;">Peserta LAKMUD</div>
                    <div style="margin-top: 60px; font-weight: bold; text-decoration: underline;">{{ $peserta->name }}</div>
                </td>
            </tr>
        </table>

        @if(!$loop->last)
            <div style="page-break-after: always;"></div>
        @endif
    @endforeach
</body>
</html>
