<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>ID Cards - Semua Peserta</title>
    <style>
        @page {
            margin: 0;
            size: 398pt 632pt;
        }
        html, body {
            margin: 0;
            padding: 0;
            width: 398pt;
            height: 632pt;
            background-color: #F5F2E9;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
        }
        .page-break {
            page-break-after: always;
        }
        .page-break:last-child {
            page-break-after: avoid;
        }
        .card-container {
            position: relative;
            width: 398pt;
            height: 632pt;
        }
        .bg-template {
            position: absolute;
            top: 0;
            left: 0;
            width: 398pt;
            height: 632pt;
            z-index: 1;
        }
        .content-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 398pt;
            height: 632pt;
            z-index: 2;
        }
        .stamp-frame {
            position: absolute;
            top: 195pt;
            left: 114pt;
            width: 170pt;
            height: 220pt;
        }
        .photo-container {
            position: absolute;
            top: 235pt;
            left: 139pt;
            width: 120pt;
            height: 120pt;
            border-radius: 50%;
            border: 4px solid #fff;
            overflow: hidden;
            background-color: #fff;
            z-index: 3;
        }
        .photo-img {
            width: 120pt;
            height: 120pt;
            display: block;
        }
        .text-nama {
            position: absolute;
            left: 145pt;
            top: 512pt;
            font-size: 14pt;
            font-weight: bold;
            color: #000;
            z-index: 3;
            width: 220pt;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            line-height: 1;
        }
        .text-delegasi {
            position: absolute;
            left: 165pt;
            top: 562pt;
            font-size: 12.5pt;
            font-weight: bold;
            color: #000;
            z-index: 3;
            width: 200pt;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            line-height: 1;
        }
    </style>
</head>
<body>

    @foreach($pesertas as $peserta)
    <div class="card-container page-break">
        <!-- Background Template Image -->
        <img class="bg-template" src="{{ public_path('storage/file_template/template_idcard.PNG') }}">

        <div class="content-overlay">
            <!-- Stamp Frame SVG -->
            <div class="stamp-frame">
                <svg width="100%" height="100%" viewBox="0 0 170 220" xmlns="http://www.w3.org/2000/svg">
                    <!-- Golden background -->
                    <rect x="0" y="0" width="170" height="220" fill="#cca034" rx="8" ry="8" />
                    <!-- Left cutouts -->
                    <circle cx="0" cy="20" r="7" fill="#F5F2E9" />
                    <circle cx="0" cy="45" r="7" fill="#F5F2E9" />
                    <circle cx="0" cy="70" r="7" fill="#F5F2E9" />
                    <circle cx="0" cy="95" r="7" fill="#F5F2E9" />
                    <circle cx="0" cy="120" r="7" fill="#F5F2E9" />
                    <circle cx="0" cy="145" r="7" fill="#F5F2E9" />
                    <circle cx="0" cy="170" r="7" fill="#F5F2E9" />
                    <circle cx="0" cy="195" r="7" fill="#F5F2E9" />
                    <!-- Right cutouts -->
                    <circle cx="170" cy="20" r="7" fill="#F5F2E9" />
                    <circle cx="170" cy="45" r="7" fill="#F5F2E9" />
                    <circle cx="170" cy="70" r="7" fill="#F5F2E9" />
                    <circle cx="170" cy="95" r="7" fill="#F5F2E9" />
                    <circle cx="170" cy="120" r="7" fill="#F5F2E9" />
                    <circle cx="170" cy="145" r="7" fill="#F5F2E9" />
                    <circle cx="170" cy="170" r="7" fill="#F5F2E9" />
                    <circle cx="170" cy="195" r="7" fill="#F5F2E9" />
                    <!-- Top cutouts -->
                    <circle cx="20" cy="0" r="7" fill="#F5F2E9" />
                    <circle cx="45" cy="0" r="7" fill="#F5F2E9" />
                    <circle cx="70" cy="0" r="7" fill="#F5F2E9" />
                    <circle cx="95" cy="0" r="7" fill="#F5F2E9" />
                    <circle cx="120" cy="0" r="7" fill="#F5F2E9" />
                    <circle cx="145" cy="0" r="7" fill="#F5F2E9" />
                    <!-- Bottom cutouts -->
                    <circle cx="20" cy="220" r="7" fill="#F5F2E9" />
                    <circle cx="45" cy="220" r="7" fill="#F5F2E9" />
                    <circle cx="70" cy="220" r="7" fill="#F5F2E9" />
                    <circle cx="95" cy="220" r="7" fill="#F5F2E9" />
                    <circle cx="120" cy="220" r="7" fill="#F5F2E9" />
                    <circle cx="145" cy="220" r="7" fill="#F5F2E9" />
                </svg>
            </div>

            <!-- Participant's Photo inside the Circle -->
            <div class="photo-container">
                @if($peserta->pendaftaran && $peserta->pendaftaran->file_foto && file_exists(public_path('storage/' . $peserta->pendaftaran->file_foto)))
                    <img class="photo-img" src="{{ public_path('storage/' . $peserta->pendaftaran->file_foto) }}">
                @else
                    <!-- Default Silhouette SVG if no photo upload exists -->
                    <svg width="120pt" height="120pt" viewBox="0 0 100 100" fill="#fff" xmlns="http://www.w3.org/2000/svg" style="background-color: #E2E8F0;">
                        <circle cx="50" cy="35" r="18" fill="#94A3B8" />
                        <path d="M15 85 C15 65, 30 55, 50 55 C70 55, 85 65, 85 85 Z" fill="#94A3B8" />
                    </svg>
                @endif
            </div>

            <!-- Participant's Name overlay -->
            <div class="text-nama">{{ $peserta->name }}</div>

            <!-- Participant's Delegation overlay -->
            <div class="text-delegasi">{{ $peserta->pendaftaran?->delegasi ?? 'PAC IPNU IPPNU KAUMAN' }}</div>
        </div>
    </div>
    @endforeach

</body>
</html>
