<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Constancias - {{ $event->title }}</title>
    <style>
        @page {
            margin: 40px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            margin: 0;
            padding: 0;
        }

        .certificate {
            border: 4px solid #1e293b;
            padding: 40px;
            height: 100%;
            box-sizing: border-box;
            text-align: center;
        }

        .header {
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #6b7280;
            margin-bottom: 20px;
        }

        .title {
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #111827;
        }

        .subtitle {
            font-size: 18px;
            margin-bottom: 40px;
            color: #374151;
        }

        .team-name {
            font-size: 26px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #111827;
        }

        .members {
            font-size: 14px;
            color: #4b5563;
            margin-bottom: 30px;
        }

        .place {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 16px;
            color: #1d4ed8;
        }

        .event-name {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .date {
            font-size: 14px;
            margin-top: 30px;
            color: #6b7280;
        }

        .footer {
            position: absolute;
            bottom: 40px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 12px;
            color: #9ca3af;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
@foreach($rankings as $index => $ranking)
    <div class="certificate">
        <div class="header">
            Constancia de participación
        </div>

        <div class="title">
            @if($ranking['place'] == 1)
                1er Lugar
            @elseif($ranking['place'] == 2)
                2do Lugar
            @else
                3er Lugar
            @endif
        </div>

        <div class="subtitle">
            Se otorga el presente reconocimiento al equipo:
        </div>

        <div class="team-name">
            {{ $ranking['team']->name }}
        </div>

        @if($ranking['team']->members->isNotEmpty())
            <div class="members">
                Integrantes: {{ $ranking['team']->members->pluck('name')->join(', ') }}
            </div>
        @endif

        <div class="event-name">
            Por su destacada participación en el evento:<br>
            <strong>{{ $event->title }}</strong>
        </div>

        <div class="date">
            Emitido el {{ $today }}
        </div>

        <div class="footer">
            Sistema de gestión de eventos
        </div>
    </div>

    @if(!$loop->last)
        <div class="page-break"></div>
    @endif
@endforeach
</body>
</html>
