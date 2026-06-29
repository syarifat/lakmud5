<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Curriculum Vitae Pemateri</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10.5pt;
            line-height: 1.4;
            color: #000;
            margin: 0;
            padding: 5px;
        }
        .title {
            text-align: center;
            font-size: 13pt;
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 20px;
            text-transform: uppercase;
        }
        .bio-container-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .bio-table {
            width: 100%;
            border-collapse: collapse;
        }
        .bio-table td {
            padding: 5px 3px;
            vertical-align: top;
            font-size: 10pt;
        }
        .bio-table td.label {
            width: 32%;
            font-weight: bold;
        }
        .bio-table td.separator {
            width: 3%;
            text-align: center;
        }
        .photo-cell {
            width: 25%;
            text-align: center;
            vertical-align: top;
            padding-left: 15px;
        }
        .photo-img {
            width: 120px;
            height: 150px;
            object-cover: cover;
            border: 1px solid #000;
            padding: 2px;
        }
        .section-title {
            font-size: 11pt;
            font-weight: bold;
            margin-top: 15px;
            margin-bottom: 8px;
            text-transform: uppercase;
            color: #00A651;
            border-bottom: 1.5px solid #00A651;
            padding-bottom: 3px;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .data-table th, .data-table td {
            border: 1px solid #000;
            padding: 5px;
            font-size: 9.5pt;
            vertical-align: middle;
        }
        .data-table th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }
        .signature-table {
            width: 100%;
            margin-top: 30px;
        }
        .signature-table td {
            text-align: right;
            padding-right: 40px;
            font-size: 10pt;
        }
        .signature-name {
            margin-top: 55px;
            font-weight: bold;
            text-decoration: underline;
        }
        @page {
            margin: 40px 40px 80px 40px;
        }
        .footer-motto {
            position: fixed;
            bottom: 30px;
            left: 40px;
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
        $items = isset($is_all) && $is_all ? $pemateris : collect([$pemateri]);
    @endphp
    @foreach($items as $pemateri)
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 15px;">
            <tr>
                <td style="width: 20%; text-align: left; vertical-align: middle; padding-bottom: 8px;">
                    <img src="{{ public_path('logo.png') }}" style="height: 140px; width: auto;">
                </td>
                <td style="width: 80%; text-align: right; vertical-align: middle; padding-bottom: 8px; padding-right: 5px;">
                    <div style="font-family: 'Times New Roman', Times, serif; font-size: 15pt; color: #00A651; font-weight: bold; line-height: 1.25; text-transform: uppercase;">
                        PANITIA PELAKSANA LATIHAN KADER MUDA V<br>
                        PIMPINAN ANAK CABANG<br>
                        IKATAN PELAJAR NAHDLATUL ULAMA<br>
                        IKATAN PELAJAR PUTRI NAHDLATUL ULAMA<br>
                        KECAMATAN KAUMAN
                    </div>
                    <div style="font-family: Arial, Helvetica, sans-serif; font-size: 8.5pt; color: #000000; font-weight: bold; line-height: 1.35; margin-top: 5px;">
                        Jln. Sidoluhur Gg. II, Dsn. Bancaan, Ds. Mojosari, Kec. Kauman - Tulungagung<br>
                        0883011340460/089617377022<br>
                        pacipippkauman@gmail.com<br>
                        pacipnuippnukauman.online
                    </div>
                </td>
            </tr>
        </table>

        <div class="title" style="margin-top: 10px;">CURRICULUM VITAE</div>

        <table class="bio-container-table">
            <tr>
                <td style="vertical-align: top;">
                    <table class="bio-table">
                        <tr>
                            <td class="label">Nama Lengkap</td>
                            <td class="separator">:</td>
                            <td>{{ $pemateri->nama }}</td>
                        </tr>
                        <tr>
                            <td class="label">Tempat, Tgl Lahir</td>
                            <td class="separator">:</td>
                            <td>{{ $pemateri->tempat_lahir }}, {{ \Carbon\Carbon::parse($pemateri->tanggal_lahir)->translatedFormat('d F Y') }}</td>
                        </tr>
                        <tr>
                            <td class="label">Alamat Lengkap</td>
                            <td class="separator">:</td>
                            <td>{{ $pemateri->alamat }}</td>
                        </tr>
                        <tr>
                            <td class="label">Nomor HP / WA</td>
                            <td class="separator">:</td>
                            <td>{{ $pemateri->no_telp }}</td>
                        </tr>
                        <tr>
                            <td class="label">Instagram</td>
                            <td class="separator">:</td>
                            <td>{{ $pemateri->instagram ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Email</td>
                            <td class="separator">:</td>
                            <td>{{ $pemateri->email ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Motto Hidup</td>
                            <td class="separator">:</td>
                            <td>"{{ $pemateri->motto }}"</td>
                        </tr>
                    </table>
                </td>
                <td class="photo-cell">
                    @if($pemateri->foto)
                        <img src="{{ public_path('storage/' . $pemateri->foto) }}" class="photo-img">
                    @else
                        <div style="width: 120px; height: 150px; border: 1px solid #ccc; line-height: 150px; font-size: 8pt; color: #777; background-color: #f9f9f9; text-align: center;">
                            Foto Kosong
                        </div>
                    @endif
                </td>
            </tr>
        </table>

        <!-- Riwayat Pendidikan -->
        <div class="section-title">Riwayat Pendidikan</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 8%;">No.</th>
                    <th style="width: 32%;">Tingkat Pendidikan</th>
                    <th>Nama Sekolah / Kampus</th>
                    <th style="width: 20%;">Tahun Lulus</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pemateri->riwayatPendidikans as $index => $pendidikan)
                    <tr>
                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        <td style="font-weight: bold; color: #444;">{{ $pendidikan->jenjang }}</td>
                        <td>{{ $pendidikan->nama_sekolah }}</td>
                        <td style="text-align: center;">{{ $pendidikan->tahun }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center; color: #777; font-style: italic;">Tidak ada data riwayat pendidikan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Riwayat Organisasi -->
        <div class="section-title">Riwayat Organisasi</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 8%;">No.</th>
                    <th>Nama Organisasi</th>
                    <th>Jabatan</th>
                    <th style="width: 20%;">Tahun / Periode</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pemateri->riwayatOrganisasis as $index => $organisasi)
                    <tr>
                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        <td style="font-weight: bold;">{{ $organisasi->nama_organisasi }}</td>
                        <td>{{ $organisasi->jabatan }}</td>
                        <td style="text-align: center;">{{ $organisasi->tahun }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center; color: #777; font-style: italic;">Tidak ada data riwayat organisasi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Riwayat Pengkaderan -->
        <div class="section-title">Riwayat Pengkaderan</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 8%;">No.</th>
                    <th style="width: 32%;">Tingkat Pengkaderan</th>
                    <th>Nama Kegiatan / Tempat</th>
                    <th style="width: 20%;">Tahun</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pemateri->riwayatPengkaderans as $index => $pengkaderan)
                    <tr>
                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        <td style="font-weight: bold; color: #444;">{{ $pengkaderan->tingkat }}</td>
                        <td>{{ $pengkaderan->nama }}</td>
                        <td style="text-align: center;">{{ $pengkaderan->tahun }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center; color: #777; font-style: italic;">Tidak ada data riwayat pengkaderan.</td>
                    </tr>
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

        @if(!$loop->last)
            <div style="page-break-after: always;"></div>
        @endif
    @endforeach
</body>
</html>
