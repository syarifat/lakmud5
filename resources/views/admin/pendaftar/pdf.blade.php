<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Rekapitulasi Data Pendaftar LAKMUD V</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
            line-height: 1.4;
            color: #000;
            margin: 0;
            padding: 10px;
        }
        .title {
            text-align: center;
            font-size: 12pt;
            font-weight: bold;
            text-decoration: underline;
            margin-top: 10px;
            margin-bottom: 20px;
            text-transform: uppercase;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .data-table th, .data-table td {
            border: 1px solid #000;
            padding: 8px 6px;
            font-size: 9pt;
            vertical-align: middle;
        }
        .data-table th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }
        .text-center {
            text-align: center;
        }
        .status-lolos {
            color: #00A651;
            font-weight: bold;
        }
        .status-pending {
            color: #d97706;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <table style="width: 100%; border-collapse: collapse; margin-bottom: 15px;">
        <tr>
            <td style="width: 18%; text-align: left; vertical-align: middle; padding-bottom: 8px;">
                <img src="{{ public_path('logo.png') }}" style="height: 140px; width: auto;">
            </td>
            <td style="width: 82%; text-align: right; vertical-align: middle; padding-bottom: 8px; padding-right: 5px;">
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

    <div class="title">REKAPITULASI DATA PENDAFTAR LAKMUD V</div>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 4%;">No.</th>
                @foreach($selected_fields as $field)
                    <th>
                        @switch($field)
                            @case('nama') Nama Lengkap @break
                            @case('email') Email @break
                            @case('nia') NIA @break
                            @case('delegasi') Delegasi @break
                            @case('ttl') Tempat, Tgl Lahir @break
                            @case('alamat') Alamat @break
                            @case('jabatan') Jabatan @break
                            @case('no_hp') No. WA @break
                            @case('username_ig') Instagram @break
                            @case('ukuran_kaos') Kaos @break
                            @case('status_lulus') Status @break
                        @endswitch
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse($pendaftar as $index => $p)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    @foreach($selected_fields as $field)
                        <td>
                            @switch($field)
                                @case('nama') {{ $p->user->name }} @break
                                @case('email') {{ $p->user->email }} @break
                                @case('nia') {{ $p->nia ?? '-' }} @break
                                @case('delegasi') {{ $p->delegasi }} @break
                                @case('ttl') {{ $p->tempat_lahir }}, {{ \Carbon\Carbon::parse($p->tanggal_lahir)->translatedFormat('d-m-Y') }} @break
                                @case('alamat') {{ $p->alamat }} @break
                                @case('jabatan') {{ $p->jabatan }} @break
                                @case('no_hp') {{ $p->no_hp }} @break
                                @case('username_ig') {{ $p->username_ig }} @break
                                @case('ukuran_kaos') {{ $p->ukuran_kaos }} @break
                                @case('status_lulus') 
                                    @if($p->status_lulus)
                                        <span class="status-lolos">Lolos</span>
                                    @else
                                        <span class="status-pending">Pending</span>
                                    @endif
                                    @break
                            @endswitch
                        </td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($selected_fields) + 1 }}" class="text-center" style="font-style: italic;">Belum ada pendaftar.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
