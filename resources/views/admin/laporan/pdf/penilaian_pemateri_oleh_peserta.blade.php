<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Lembar Penilaian Akhir Pemateri Oleh Peserta</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
            line-height: 1.3;
            color: #000;
            margin: 0;
            padding: 5px;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
        }
        .header h1 {
            font-size: 13pt;
            margin: 0;
            text-transform: uppercase;
            font-weight: bold;
        }
        .header h2 {
            font-size: 11pt;
            margin: 3px 0 0 0;
            text-transform: uppercase;
            font-weight: bold;
        }
        .header h3 {
            font-size: 10pt;
            margin: 3px 0 0 0;
            text-transform: uppercase;
            font-weight: normal;
        }
        .instructions {
            font-size: 8.5pt;
            margin-bottom: 15px;
            line-height: 1.25;
        }
        .instructions p {
            margin: 2px 0;
        }
        .meta-table {
            width: 100%;
            margin-bottom: 10px;
            font-size: 9.5pt;
            font-weight: bold;
        }
        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }
        .main-table th, .main-table td {
            border: 1px solid #000;
            padding: 5px;
            font-size: 8.5pt;
            vertical-align: middle;
        }
        .main-table th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }
        .text-center {
            text-align: center;
        }
        .check-cell {
            width: 32px;
            text-align: center;
            font-family: DejaVu Sans, sans-serif;
        }
        .signature-table {
            width: 100%;
            margin-top: 30px;
            font-size: 9.5pt;
        }
        .signature-table td {
            vertical-align: top;
        }
    </style>
</head>
<body>

    <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
        <tr>
            <td style="width: 20%; text-align: left; vertical-align: middle; padding-bottom: 8px;">
                <img src="{{ public_path('logo.png') }}" style="height: 110px; width: auto;">
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
        <h1 style="font-size: 12pt; font-weight: bold; text-transform: uppercase;">LEMBAR NILAI AKHIR PEMATERI OLEH PESERTA</h1>
        <h2 style="font-size: 11pt; font-weight: bold; text-transform: uppercase; margin-top: 3px; color: #444;">LATIHAN KADER MUDA</h2>
    </div>

    <div class="instructions">
        <strong>Petunjuk Teknis:</strong>
        <p>1. Lembar ini menjadi pegangan peserta.</p>
        <p>2. Beri nilai pemateri dengan skala 50 – 90.</p>
        <p>3. Tambahkan catatan khusus jika diperlukan.</p>
        <p>4. Konsultasikan kepada pendamping jika bermasalah dengan pemateri.</p>
    </div>

    <table class="meta-table">
        <tr>
            <td>Nama Peserta : {{ $peserta->name }}</td>
            <td style="text-align: right;">Delegasi : {{ $peserta->pendaftaran ? $peserta->pendaftaran->delegasi : '-' }}</td>
        </tr>
    </table>

    <table class="main-table">
        <thead>
            <tr>
                <th rowspan="2" style="width: 5%;">NO.</th>
                <th rowspan="2" style="width: 35%;">MATERI</th>
                <th rowspan="2" style="width: 35%;">CATATAN KHUSUS</th>
                <th colspan="5">NILAI</th>
            </tr>
            <tr>
                <th class="check-cell">50</th>
                <th class="check-cell">60</th>
                <th class="check-cell">70</th>
                <th class="check-cell">80</th>
                <th class="check-cell">90</th>
            </tr>
        </thead>
        <tbody>
            @foreach($jadwals as $index => $jadwal)
                @php
                    $rating = $ratings->get($jadwal->id);
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}.</td>
                    <td>{{ $jadwal->materi->nama_materi }}</td>
                    <td>{{ $rating ? $rating->catatan_khusus : '' }}</td>
                    
                    <!-- Nilai check -->
                    <td class="text-center">{{ ($rating && $rating->nilai == 50) ? '√' : '' }}</td>
                    <td class="text-center">{{ ($rating && $rating->nilai == 60) ? '√' : '' }}</td>
                    <td class="text-center">{{ ($rating && $rating->nilai == 70) ? '√' : '' }}</td>
                    <td class="text-center">{{ ($rating && $rating->nilai == 80) ? '√' : '' }}</td>
                    <td class="text-center">{{ ($rating && $rating->nilai == 90) ? '√' : '' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

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

</body>
</html>
