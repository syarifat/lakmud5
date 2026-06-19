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

    <div class="header">
        <h1>DAFTAR PERTANYAAN</h1>
        <h2>PRETEST – POSTTEST</h2>
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
