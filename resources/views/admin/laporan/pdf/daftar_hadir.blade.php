<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Daftar Hadir Peserta</title>
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
        .meta-container {
            margin-bottom: 15px;
            font-size: 10pt;
        }
        .meta-table {
            width: 100%;
            border-collapse: collapse;
        }
        .meta-table td {
            padding: 3px 0;
            vertical-align: top;
        }
        .meta-table td.label {
            width: 15%;
        }
        .meta-table td.separator {
            width: 2%;
            text-align: center;
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
        .signature-container {
            height: 35px;
            display: block;
            text-align: left;
        }
        .signature-container svg {
            height: 35px;
            width: auto;
            max-width: 120px;
        }
        .placeholder-sig {
            font-size: 9pt;
            color: #555;
            padding-left: 5px;
        }
    </style>
</head>
<body>

    <table style="width: 100%; border-collapse: collapse; border-bottom: 4px double #000000; padding-bottom: 8px; margin-bottom: 20px;">
        <tr>
            <td style="width: 15%; text-align: left; vertical-align: middle; padding-bottom: 8px;">
                <img src="{{ public_path('logo.png') }}" style="height: 80px; width: auto;">
            </td>
            <td style="width: 85%; text-align: center; vertical-align: middle; padding-bottom: 8px; padding-right: 30px;">
                <div style="font-family: 'Times New Roman', Times, serif; font-size: 14pt; color: #00A651; font-weight: bold; line-height: 1.2; text-transform: uppercase;">
                    PANITIA PELAKSANA LATIHAN KADER MUDA<br>
                    PIMPINAN ANAK CABANG<br>
                    IKATAN PELAJAR NAHDLATUL ULAMA<br>
                    IKATAN PELAJAR PUTRI NAHDLATUL ULAMA
                </div>
                <div style="font-family: Arial, Helvetica, sans-serif; font-size: 9pt; color: #FF0000; font-weight: bold; margin-top: 4px; text-transform: uppercase;">
                    KECAMATAN KAUMAN
                </div>
                <div style="font-family: Arial, Helvetica, sans-serif; font-size: 9pt; color: #FF0000; font-weight: bold; line-height: 1.3; margin-top: 3px;">
                    Kantor PCNU Lt. I, Jl. Pattimura Gg. II No. 09 Gedangsewu – Boyolangu – Tulungagung<br>
                    08563500282 / 085720450149 | ipnutulungagungsiap@gmail.com | www.pcipnu-ippnutulungagung.or.id
                </div>
            </td>
        </tr>
    </table>

    <div class="header" style="text-align: center; margin-top: 15px; margin-bottom: 15px;">
        <h1 style="font-size: 12pt; font-weight: bold; text-transform: uppercase;">DAFTAR HADIR PESERTA</h1>
        <h2 style="font-size: 11pt; font-weight: bold; text-transform: uppercase; margin-top: 3px; color: #444;">LATIHAN KADER MUDA</h2>
    </div>

    <div class="meta-container">
        <table class="meta-table">
            <tr>
                <td class="label">Nama Pemateri</td>
                <td class="separator">:</td>
                <td>{{ $jadwal->pemateri->nama }}</td>
            </tr>
            <tr>
                <td class="label">Materi</td>
                <td class="separator">:</td>
                <td>{{ $jadwal->materi->nama_materi }}</td>
            </tr>
            <tr>
                <td class="label">Waktu / Sesi</td>
                <td class="separator">:</td>
                <td>{{ \Carbon\Carbon::parse($jadwal->waktu_mulai)->translatedFormat('l, d F Y (H:i') }} - {{ \Carbon\Carbon::parse($jadwal->waktu_selesai)->format('H:i') }})</td>
            </tr>
        </table>
    </div>

    <table class="main-table">
        <thead>
            <tr>
                <th style="width: 8%;">NO</th>
                <th style="width: 40%;">NAMA</th>
                <th style="width: 27%;">DELEGASI</th>
                <th style="width: 25%;">TTD</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pesertas as $index => $peserta)
                @php
                    $hasAbsen = $peserta->absensis->isNotEmpty();
                    $pendaftaran = $peserta->pendaftaran;
                    $sigSvg = null;
                    if ($hasAbsen && $pendaftaran && $pendaftaran->file_ttd) {
                        try {
                            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($pendaftaran->file_ttd)) {
                                $sigSvg = \Illuminate\Support\Facades\Storage::disk('public')->get($pendaftaran->file_ttd);
                            }
                        } catch (\Exception $e) {
                            $sigSvg = null;
                        }
                    }
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}.</td>
                    <td>{{ $peserta->name }}</td>
                    <td>{{ $pendaftaran ? $pendaftaran->delegasi : '-' }}</td>
                    <td>
                        @if($hasAbsen)
                            @if($sigSvg)
                                <div class="signature-container">
                                    {!! $sigSvg !!}
                                </div>
                            @else
                                <span class="placeholder-sig" style="color: green; font-weight: bold;">✔ Hadir</span>
                            @endif
                        @else
                            <span class="placeholder-sig">{{ $index + 1 }}. ....................</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
