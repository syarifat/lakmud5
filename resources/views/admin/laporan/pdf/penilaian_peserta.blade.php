<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Lembar Penilaian Peserta</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.4;
            color: #000;
            margin: 0;
            padding: 10px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
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
        .info-section {
            margin-bottom: 15px;
            font-size: 10pt;
        }
        .info-section p {
            margin: 3px 0;
        }
        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .main-table th, .main-table td {
            border: 1px solid #000;
            padding: 6px;
            font-size: 10pt;
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
    </style>
</head>
<body>

    <table style="width: 100%; border-collapse: collapse; border-bottom: 4px double #000000; padding-bottom: 8px; margin-bottom: 20px;">
        <tr>
            <td style="width: 15%; text-align: left; vertical-align: middle; padding-bottom: 8px;">
                <img src="{{ public_path('logo.png') }}" style="height: 85px; width: auto;">
            </td>
            <td style="width: 85%; text-align: right; vertical-align: middle; padding-bottom: 8px; padding-right: 5px;">
                <div style="font-family: 'Times New Roman', Times, serif; font-size: 14pt; color: #00A651; font-weight: bold; line-height: 1.25; text-transform: uppercase;">
                    PANITIA PELAKSANA LATIHAN KADER MUDA V<br>
                    PIMPINAN ANAK CABANG<br>
                    IKATAN PELAJAR NAHDLATUL ULAMA<br>
                    IKATAN PELAJAR PUTRI NAHDLATUL ULAMA<br>
                    KECAMATAN KAUMAN
                </div>
                <div style="font-family: Arial, Helvetica, sans-serif; font-size: 9pt; color: #000000; font-weight: bold; line-height: 1.3; margin-top: 5px;">
                    Jln. Sidoluhur Gg. II, Dsn. Bancaan, Ds. Mojosari, Kec. Kauman - Tulungagung<br>
                    0883011340460/089617377022<br>
                    pacipippkauman@gmail.com<br>
                    pacipnuippnukauman.online
                </div>
            </td>
        </tr>
    </table>

    <div class="header" style="text-align: center; margin-top: 15px; margin-bottom: 15px;">
        <h1 style="font-size: 12pt; font-weight: bold; text-transform: uppercase;">LEMBAR PENILAIAN PESERTA</h1>
        <h2 style="font-size: 11pt; font-weight: bold; text-transform: uppercase; margin-top: 3px; color: #444;">LATIHAN KADER MUDA</h2>
    </div>

    <div class="info-section">
        <p><strong>Materi:</strong> {{ $jadwal->materi->nama_materi }}</p>
        <p><strong>Instrumen Penilaian:</strong></p>
        <p style="margin-left: 20px; font-size: 9.5pt; line-height: 1.3;">
            - Pemahaman: Evaluasi materi (soal, resume)<br>
            - Kedisiplinan: Absensi selama kegiatan, sikap (khusus LAKMUD)<br>
            - Keaktifan: Bertanya, menjawab, menanggapi, dll.<br>
            - Nilai Rerata: Hasil penjumlahan 3 unsur penilaian dibagi 3<br>
            - Skala nilai: 70 – 100
        </p>
    </div>

    <table class="main-table">
        <thead>
            <tr>
                <th style="width: 8%;">No.</th>
                <th>Nama</th>
                <th style="width: 18%;">Pemahaman</th>
                <th style="width: 18%;">Kedisiplinan</th>
                <th style="width: 18%;">Keaktifan</th>
                <th style="width: 15%;">Rerata</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pesertas as $index => $peserta)
                @php
                    $penilaian = $peserta->penilaianPesertas->first();
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $peserta->name }}</td>
                    <td class="text-center">{{ $penilaian ? $penilaian->pemahaman : '-' }}</td>
                    <td class="text-center">{{ $penilaian ? $penilaian->kedisiplinan : '-' }}</td>
                    <td class="text-center">{{ $penilaian ? $penilaian->keaktifan : '-' }}</td>
                    <td class="text-center" style="font-weight: bold;">{{ $penilaian ? number_format($penilaian->rerata, 2) : '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
