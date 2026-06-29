<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Lembar Observasi Harian Peserta</title>
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
            padding: 4px;
            font-size: 8.5pt;
            vertical-align: middle;
            text-align: center;
        }
        .main-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .main-table td.name-cell {
            text-align: left;
            padding-left: 6px;
        }
        .check-cell {
            width: 18px;
            font-family: DejaVu Sans, sans-serif; /* For better checkmark support */
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
    @php
        $pages = isset($is_all) && $is_all ? $reportData : collect([['kelompok' => $kelompok, 'pesertas' => $pesertas, 'hari_ke' => $hari_ke, 'observasis' => $observasis]]);
    @endphp
    @foreach($pages as $page)
        @php
            $kelompok = $page['kelompok'];
            $pesertas = $page['pesertas'];
            $hari_ke = $page['hari_ke'];
            $observasis = $page['observasis'];
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
            <h1 style="font-size: 12pt; font-weight: bold; text-transform: uppercase;">LEMBAR OBSERVASI HARIAN</h1>
            <h2 style="font-size: 11pt; font-weight: bold; text-transform: uppercase; margin-top: 3px; color: #444;">PESERTA LATIHAN KADER MUDA</h2>
        </div>

        <div class="instructions">
            <strong>Petunjuk Teknis :</strong>
            <p>1. Lembar ini menjadi pegangan pendamping.</p>
            <p>2. Pendamping mengobservasi peserta dalam kondisi waktu untuk 1 hari pelaksanaan.</p>
            <p>3. Penilaian antar aspek dapat diambilkan/berdasar pada seluruh kegiatan pelatihan di hari itu.</p>
            <p>4. Pendamping diperkenankan untuk menanyakan/mendiskusikan/mencari informasi lain guna menunjang penilaian terhadap peserta tertentu.</p>
            <p>5. Nilai angka wajib diisi oleh pendamping disetiap harinya, adapun skala nilai angka adalah 40 – 80.</p>
            <p>6. Berilah tanda (√) pada kolom yang tersedia.</p>
            <p>7. Keterangan : (1) sangat kurang, (2) Kurang, (3) Cukup, (4) Baik, (5) Sangat Baik</p>
        </div>

        <table class="meta-table">
            <tr>
                <td style="width: 50%;">Kelompok : {{ $kelompok->nama_kelompok }}</td>
                <td style="width: 50%; text-align: right;">Pendamping : {{ $kelompok->pendamping->name }} &nbsp;&nbsp;|&nbsp;&nbsp; Hari Ke-{{ $hari_ke }}</td>
            </tr>
        </table>

        <table class="main-table">
            <thead>
                <tr>
                    <th rowspan="2" style="width: 5%;">No.</th>
                    <th rowspan="2" style="width: 25%;">Nama Peserta</th>
                    <th colspan="5">Kedisiplinan</th>
                    <th colspan="5">Kemampuan</th>
                    <th colspan="5">Keaktifan</th>
                    <th rowspan="2" style="width: 10%;">Nilai Angka</th>
                </tr>
                <tr>
                    <th class="check-cell">1</th>
                    <th class="check-cell">2</th>
                    <th class="check-cell">3</th>
                    <th class="check-cell">4</th>
                    <th class="check-cell">5</th>
                    <th class="check-cell">1</th>
                    <th class="check-cell">2</th>
                    <th class="check-cell">3</th>
                    <th class="check-cell">4</th>
                    <th class="check-cell">5</th>
                    <th class="check-cell">1</th>
                    <th class="check-cell">2</th>
                    <th class="check-cell">3</th>
                    <th class="check-cell">4</th>
                    <th class="check-cell">5</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pesertas as $index => $peserta)
                    @php
                        $obs = isset($observasis) ? $observasis->get($peserta->id) : null;
                    @endphp
                    <tr>
                        <td>{{ $index + 1 }}.</td>
                        <td class="name-cell">{{ $peserta->name }}</td>
                        
                        <!-- Kedisiplinan checks -->
                        <td>{{ ($obs && $obs->kedisiplinan == 1) ? '√' : '' }}</td>
                        <td>{{ ($obs && $obs->kedisiplinan == 2) ? '√' : '' }}</td>
                        <td>{{ ($obs && $obs->kedisiplinan == 3) ? '√' : '' }}</td>
                        <td>{{ ($obs && $obs->kedisiplinan == 4) ? '√' : '' }}</td>
                        <td>{{ ($obs && $obs->kedisiplinan == 5) ? '√' : '' }}</td>

                        <!-- Kemampuan checks -->
                        <td>{{ ($obs && $obs->kemampuan == 1) ? '√' : '' }}</td>
                        <td>{{ ($obs && $obs->kemampuan == 2) ? '√' : '' }}</td>
                        <td>{{ ($obs && $obs->kemampuan == 3) ? '√' : '' }}</td>
                        <td>{{ ($obs && $obs->kemampuan == 4) ? '√' : '' }}</td>
                        <td>{{ ($obs && $obs->kemampuan == 5) ? '√' : '' }}</td>

                        <!-- Keaktifan checks -->
                        <td>{{ ($obs && $obs->keaktifan == 1) ? '√' : '' }}</td>
                        <td>{{ ($obs && $obs->keaktifan == 2) ? '√' : '' }}</td>
                        <td>{{ ($obs && $obs->keaktifan == 3) ? '√' : '' }}</td>
                        <td>{{ ($obs && $obs->keaktifan == 4) ? '√' : '' }}</td>
                        <td>{{ ($obs && $obs->keaktifan == 5) ? '√' : '' }}</td>

                        <td style="font-weight: bold;">{{ $obs ? number_format($obs->nilai_angka, 0) : '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <table class="signature-table">
            <tr>
                <td style="width: 60%;"></td>
                <td style="text-align: right; padding-right: 40px;">
                    <div>Tulungagung, {{ now()->translatedFormat('d F Y') }}</div>
                    <div style="margin-top: 5px;">Pendamping Kelompok</div>
                    <div style="margin-top: 60px; font-weight: bold; text-decoration: underline;">{{ $kelompok->pendamping->name }}</div>
                </td>
            </tr>
        </table>
        @if(!$loop->last)
            <div style="page-break-after: always;"></div>
        @endif
    @endforeach
</body>
</html>
