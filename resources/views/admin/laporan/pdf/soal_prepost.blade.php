<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Daftar Pertanyaan Pretest - Posttest</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
            line-height: 1.4;
            color: #000;
            margin: 0;
            padding: 5px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 13pt;
            margin: 0;
            text-transform: uppercase;
            font-weight: bold;
        }
        .header h2 {
            font-size: 11pt;
            margin: 4px 0 0 0;
            text-transform: uppercase;
            font-weight: bold;
        }
        .grid-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .grid-table td {
            border: 1px solid #000;
            padding: 10px;
            vertical-align: top;
            width: 50%;
        }
        .materi-title {
            font-weight: bold;
            font-size: 10pt;
            background-color: #f2f2f2;
            padding: 4px;
            border-bottom: 1px solid #000;
            text-align: center;
            margin-bottom: 8px;
            text-transform: uppercase;
        }
        .question-list {
            margin: 0;
            padding-left: 15px;
            font-size: 9pt;
        }
        .question-list li {
            margin-bottom: 5px;
            text-align: justify;
        }
        .single-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .single-table th, .single-table td {
            border: 1px solid #000;
            padding: 8px;
            font-size: 9.5pt;
            vertical-align: top;
        }
        .single-table th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }
        .type-badge {
            font-weight: bold;
            text-transform: uppercase;
            font-size: 8.5pt;
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
        <h1 style="font-size: 12pt; font-weight: bold; text-transform: uppercase;">DAFTAR PERTANYAAN</h1>
        <h2 style="font-size: 11pt; font-weight: bold; text-transform: uppercase; margin-top: 3px; color: #444;">PRETEST – POSTTEST</h2>
    </div>

    @if($materis->count() == 1)
        <!-- Single Material View -->
        @php $materi = $materis->first(); @endphp
        <div style="margin-bottom: 15px; font-weight: bold; font-size: 11pt;">
            MATERI: {{ $materi->nama_materi }}
        </div>

        <table class="single-table">
            <thead>
                <tr>
                    <th style="width: 8%;">NO</th>
                    <th style="width: 18%;">TIPE</th>
                    <th>PERTANYAAN</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $materiQuestions = $questions->get($materi->id) ?? collect();
                @endphp
                @forelse($materiQuestions as $index => $q)
                    <tr>
                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        <td style="text-align: center;">
                            <span class="type-badge" style="color: {{ $q->tipe == 'pretest' ? '#1d4ed8' : '#b91c1c' }};">
                                {{ $q->tipe }}
                            </span>
                        </td>
                        <td>{{ $q->pertanyaan }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" style="text-align: center; font-style: italic; color: #666;">Belum ada pertanyaan untuk materi ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @else
        <!-- Grid view for all materials (Official .docm Layout) -->
        <table class="grid-table">
            <tbody>
                @php
                    $chunks = $materis->chunk(2);
                @endphp
                @foreach($chunks as $chunk)
                    <tr>
                        @foreach($chunk as $materi)
                            @php
                                $materiQuestions = $questions->get($materi->id) ?? collect();
                            @endphp
                            <td>
                                <div class="materi-title">{{ $materi->nama_materi }}</div>
                                @if($materiQuestions->isNotEmpty())
                                    <ol class="question-list">
                                        @foreach($materiQuestions as $q)
                                            <li>
                                                <strong>[{{ ucfirst($q->tipe) }}]</strong> {{ $q->pertanyaan }}
                                            </li>
                                        @endforeach
                                    </ol>
                                @else
                                    <div style="font-size: 8.5pt; font-style: italic; color: #888; text-align: center; margin-top: 10px;">
                                        Belum ada pertanyaan.
                                    </div>
                                @endif
                            </td>
                        @endforeach
                        <!-- Add empty cell if chunk has only 1 element -->
                        @if($chunk->count() == 1)
                            <td></td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

</body>
</html>
