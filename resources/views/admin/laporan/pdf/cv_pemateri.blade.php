<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Curriculum Vitae Pemateri</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.5;
            color: #000;
            margin: 0;
            padding: 10px;
        }
        .title {
            text-align: center;
            font-size: 14pt;
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 25px;
            text-transform: uppercase;
        }
        .bio-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .bio-table td {
            padding: 6px 4px;
            vertical-align: top;
        }
        .bio-table td.label {
            width: 25%;
        }
        .bio-table td.separator {
            width: 3%;
            text-align: center;
        }
        .section-title {
            font-size: 11pt;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .data-table th, .data-table td {
            border: 1px solid #000;
            padding: 6px;
            font-size: 10pt;
            vertical-align: top;
        }
        .data-table th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }
        .signature-table {
            width: 100%;
            margin-top: 40px;
        }
        .signature-table td {
            text-align: right;
            padding-right: 50px;
        }
        .signature-name {
            margin-top: 60px;
            font-weight: bold;
            text-decoration: underline;
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
                <div style="font-family: Arial, Helvetica, sans-serif; font-size: 10.5pt; color: #FF0000; font-weight: bold; margin-top: 4px; text-transform: uppercase;">
                    KECAMATAN KAUMAN
                </div>
                <div style="font-family: Arial, Helvetica, sans-serif; font-size: 9pt; color: #FF0000; font-weight: bold; line-height: 1.3; margin-top: 3px;">
                    Kantor PCNU Lt. I, Jl. Pattimura Gg. II No. 09 Gedangsewu – Boyolangu – Tulungagung<br>
                    08563500282 / 085720450149 | ipnutulungagungsiap@gmail.com | www.pcipnu-ippnutulungagung.or.id
                </div>
            </td>
        </tr>
    </table>

    <div class="title" style="margin-top: 15px;">CURRICULUM VITAE</div>

    <table class="bio-table">
        <tr>
            <td class="label">Nama</td>
            <td class="separator">:</td>
            <td>{{ $pemateri->nama }}</td>
        </tr>
        <tr>
            <td class="label">Tempat Tanggal Lahir</td>
            <td class="separator">:</td>
            <td>{{ $pemateri->tempat_lahir }}, {{ \Carbon\Carbon::parse($pemateri->tanggal_lahir)->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td class="label">Alamat</td>
            <td class="separator">:</td>
            <td>{{ $pemateri->alamat }}</td>
        </tr>
        <tr>
            <td class="label">Hobi</td>
            <td class="separator">:</td>
            <td>{{ $pemateri->hobi }}</td>
        </tr>
        <tr>
            <td class="label">Motto</td>
            <td class="separator">:</td>
            <td>{{ $pemateri->motto }}</td>
        </tr>
        <tr>
            <td class="label">Nomor Telepon</td>
            <td class="separator">:</td>
            <td>{{ $pemateri->no_telp }}</td>
        </tr>
        <tr>
            <td class="label">Pekerjaan</td>
            <td class="separator">:</td>
            <td>{{ $pemateri->pekerjaan }}</td>
        </tr>
    </table>

    <div class="section-title">Riwayat pendidikan :</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 8%;">No.</th>
                <th style="width: 25%;">Jenjang</th>
                <th>Nama Sekolah</th>
                <th style="width: 20%;">Tahun</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pemateri->riwayatPendidikans as $index => $pendidikan)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ $pendidikan->jenjang }}</td>
                    <td>{{ $pendidikan->nama_sekolah }}</td>
                    <td style="text-align: center;">{{ $pendidikan->tahun }}</td>
                </tr>
            @empty
                <!-- Default empty rows as in original template docm -->
                <tr><td style="text-align: center;">1</td><td>Dasar</td><td></td><td></td></tr>
                <tr><td style="text-align: center;">2</td><td>SLTP</td><td></td><td></td></tr>
                <tr><td style="text-align: center;">3</td><td>SLTA</td><td></td><td></td></tr>
                <tr><td style="text-align: center;">4</td><td>S1</td><td></td><td></td></tr>
                <tr><td style="text-align: center;">5</td><td>S2</td><td></td><td></td></tr>
                <tr><td style="text-align: center;">6</td><td>S3</td><td></td><td></td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="section-title">Riwayat Organisasi :</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 8%;">No.</th>
                <th>Nama Organisasi</th>
                <th>Jabatan</th>
                <th style="width: 20%;">Tahun</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pemateri->riwayatOrganisasis as $index => $organisasi)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ $organisasi->nama_organisasi }}</td>
                    <td>{{ $organisasi->jabatan }}</td>
                    <td style="text-align: center;">{{ $organisasi->tahun }}</td>
                </tr>
            @empty
                <!-- Default empty rows as in original template docm -->
                <tr><td style="text-align: center;">1</td><td></td><td></td><td></td></tr>
                <tr><td style="text-align: center;">2</td><td></td><td></td><td></td></tr>
                <tr><td style="text-align: center;">3</td><td></td><td></td><td></td></tr>
                <tr><td style="text-align: center;">4</td><td></td><td></td><td></td></tr>
                <tr><td style="text-align: center;">5</td><td></td><td></td><td></td></tr>
            @endforelse
        </tbody>
    </table>

    <table class="signature-table">
        <tr>
            <td>
                <div>Tulungagung, {{ now()->translatedFormat('d F Y') }}</div>
                <div class="signature-name">({{ $pemateri->nama }})</div>
            </td>
        </tr>
    </table>

</body>
</html>
