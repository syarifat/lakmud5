<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>ID Card - {{ $peserta->name }}</title>
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
        .stamp-bg {
            position: absolute;
            top: 195pt;
            left: 114pt;
            width: 170pt;
            height: 220pt;
            background-color: #cca034;
            border-radius: 8pt;
            z-index: 2;
        }
        .dot {
            position: absolute;
            width: 14pt;
            height: 14pt;
            border-radius: 50%;
            background-color: #F5F2E9;
            z-index: 3;
        }
        .dot.left {
            left: -7pt;
        }
        .dot.right {
            right: -7pt;
        }
        .dot.top {
            top: -7pt;
        }
        .dot.bottom {
            bottom: -7pt;
        }
        /* Removed photo-container and photo-img styles */
    </style>
</head>
<body>

    <div class="card-container">
        <!-- Background Template Image -->
        <img class="bg-template" src="{{ public_path('storage/file_template/template_idcard.PNG') }}">

        <!-- Stamp Background with CSS Perforation -->
        <div class="stamp-bg">
            <!-- Left edge perforation -->
            <div class="dot left" style="top: 20pt;"></div>
            <div class="dot left" style="top: 45pt;"></div>
            <div class="dot left" style="top: 70pt;"></div>
            <div class="dot left" style="top: 95pt;"></div>
            <div class="dot left" style="top: 120pt;"></div>
            <div class="dot left" style="top: 145pt;"></div>
            <div class="dot left" style="top: 170pt;"></div>
            <div class="dot left" style="top: 195pt;"></div>

            <!-- Right edge perforation -->
            <div class="dot right" style="top: 20pt;"></div>
            <div class="dot right" style="top: 45pt;"></div>
            <div class="dot right" style="top: 70pt;"></div>
            <div class="dot right" style="top: 95pt;"></div>
            <div class="dot right" style="top: 120pt;"></div>
            <div class="dot right" style="top: 145pt;"></div>
            <div class="dot right" style="top: 170pt;"></div>
            <div class="dot right" style="top: 195pt;"></div>

            <!-- Top edge perforation -->
            <div class="dot top" style="left: 20pt;"></div>
            <div class="dot top" style="left: 45pt;"></div>
            <div class="dot top" style="left: 70pt;"></div>
            <div class="dot top" style="left: 95pt;"></div>
            <div class="dot top" style="left: 120pt;"></div>
            <div class="dot top" style="left: 145pt;"></div>

            <!-- Bottom edge perforation -->
            <div class="dot bottom" style="left: 20pt;"></div>
            <div class="dot bottom" style="left: 45pt;"></div>
            <div class="dot bottom" style="left: 70pt;"></div>
            <div class="dot bottom" style="left: 95pt;"></div>
            <div class="dot bottom" style="left: 120pt;"></div>
            <div class="dot bottom" style="left: 145pt;"></div>

            <!-- Participant's Photo or Silhouette Placeholder -->
            @if($peserta->pendaftaran && $peserta->pendaftaran->file_foto && file_exists(public_path('storage/' . $peserta->pendaftaran->file_foto)))
                <img src="{{ public_path('storage/' . $peserta->pendaftaran->file_foto) }}" style="position: absolute; top: 0; left: 0; width: 170pt; height: 220pt; object-fit: cover; border-radius: 8pt; z-index: 2;">
            @else
                <!-- Default Silhouette Placeholder (renders only if no photo upload exists) -->
                <!-- White circle head -->
                <div style="position: absolute; top: 40pt; left: 50pt; width: 70pt; height: 70pt; border-radius: 50%; background-color: #fff; z-index: 2;"></div>
                <!-- White arch body -->
                <div style="position: absolute; bottom: 0; left: 20pt; width: 130pt; height: 75pt; border-radius: 65pt 65pt 0 0; background-color: #fff; z-index: 2;"></div>
            @endif
        </div>

        <!-- Row Nama -->
        <table style="position: absolute; left: 45pt; top: 548pt; width: 310pt; border-collapse: collapse; z-index: 3; font-family: 'Helvetica Neue', Arial, sans-serif;">
            <tr>
                <td style="width: 80pt; font-size: 15pt; font-weight: bold; color: #0d2a4a; padding-bottom: 2px; vertical-align: bottom; line-height: 1;">Nama:</td>
                <td style="font-size: 14.5pt; font-weight: bold; color: #000; border-bottom: 2.5px solid #0d2a4a; padding-bottom: 2px; vertical-align: bottom; text-align: left; line-height: 1;">
                    {{ $peserta->name }}
                </td>
            </tr>
        </table>

        <!-- Row Delegasi -->
        <table style="position: absolute; left: 45pt; top: 594pt; width: 310pt; border-collapse: collapse; z-index: 3; font-family: 'Helvetica Neue', Arial, sans-serif;">
            <tr>
                <td style="width: 80pt; font-size: 15pt; font-weight: bold; color: #0d2a4a; padding-bottom: 2px; vertical-align: bottom; line-height: 1;">Delegasi:</td>
                <td style="font-size: 13.5pt; font-weight: bold; color: #000; border-bottom: 2.5px solid #0d2a4a; padding-bottom: 2px; vertical-align: bottom; text-align: left; line-height: 1;">
                    {{ $peserta->pendaftaran?->delegasi ?? 'Belum Ditentukan' }}
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
