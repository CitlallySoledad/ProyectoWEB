<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.6;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #2563eb;
            margin-bottom: 20px;
            padding-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            color: #2563eb;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .section-title {
            background: #2563eb;
            color: white;
            padding: 10px 15px;
            margin-top: 20px;
            margin-bottom: 10px;
            font-weight: bold;
            font-size: 14px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .info-table td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        .info-table td:first-child {
            font-weight: bold;
            background: #f5f5f5;
            width: 30%;
        }
        .criteria-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 12px;
        }
        .criteria-table th {
            background: #0f3b57;
            color: white;
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        .criteria-table td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        .criteria-table tr:nth-child(even) {
            background: #f9f9f9;
        }
        .final-score {
            background: #dbeafe;
            padding: 15px;
            border-left: 4px solid #2563eb;
            margin: 15px 0;
            font-size: 16px;
            font-weight: bold;
        }
        .evidence-list {
            list-style: none;
            padding: 0;
        }
        .evidence-item {
            background: #f5f5f5;
            padding: 10px;
            margin: 8px 0;
            border-left: 3px solid #fbbf24;
        }
        .evidence-item strong {
            display: block;
        }
        .evidence-item small {
            display: block;
            color: #666;
            margin-top: 5px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            border-top: 1px solid #ddd;
            padding-top: 15px;
            font-size: 11px;
            color: #999;
        }
        .score-badge {
            display: inline-block;
            background: #2563eb;
            color: white;
            padding: 3px 8px;
            border-radius: 3px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📊 Reporte de Evaluación</h1>
        <p>Generado el: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="section-title">Información General</div>
    <table class="info-table">
        <tr>
            <td>Proyecto:</td>
            <td>{{ $evaluation->project->name }}</td>
        </tr>
        <tr>
            <td>Equipo:</td>
            <td>{{ $evaluation->project->team?->name ?? '—' }}</td>
        </tr>
        <tr>
            <td>Rúbrica:</td>
            <td>{{ $evaluation->rubric->name ?? '—' }}</td>
        </tr>
        <tr>
            <td>Juez:</td>
            <td>{{ $evaluation->judge->name }}</td>
        </tr>
        <tr>
            <td>Estado:</td>
            <td>{{ ucfirst($evaluation->status) }}</td>
        </tr>
        <tr>
            <td>Fecha de Evaluación:</td>
            <td>{{ $evaluation->evaluated_at?->format('d/m/Y H:i') ?? '—' }}</td>
        </tr>
    </table>

    <div class="section-title">Criterios Evaluados</div>
    <table class="criteria-table">
        <thead>
            <tr>
                <th style="width: 40%;">Criterio</th>
                <th style="width: 10%;">Puntaje</th>
                <th style="width: 50%;">Comentario</th>
            </tr>
        </thead>
        <tbody>
            @foreach($evaluation->rubric->criteria ?? [] as $criterion)
                @php
                    $score = $evaluation->scores->firstWhere('rubric_criterion_id', $criterion->id);
                @endphp
                <tr>
                    <td><strong>{{ $criterion->name }}</strong></td>
                    <td style="text-align: center;">
                        @if($score)
                            <span class="score-badge">{{ $score->score }}/10</span>
                        @else
                            <span style="color: #ccc;">—</span>
                        @endif
                    </td>
                    <td>{{ $score->comment ?? '—' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="final-score">
        ⭐ Calificación Final: {{ number_format($evaluation->final_score ?? 0, 2) }} / 10
    </div>

    @if($evaluation->general_comments)
        <div class="section-title">Comentarios Generales</div>
        <p style="background: #fafafa; padding: 12px; border-left: 3px solid #fbbf24; margin-bottom: 15px;">
            {{ $evaluation->general_comments }}
        </p>
    @endif

    @if($evaluation->evidences && $evaluation->evidences->count() > 0)
        <div class="section-title">Evidencias Adjuntas</div>
        <ul class="evidence-list">
            @foreach($evaluation->evidences as $evidence)
                <li class="evidence-item">
                    <strong>📎 {{ $evidence->file_name }}</strong>
                    <small>
                        {{ $evidence->description ?? 'Sin descripción' }} | 
                        {{ round($evidence->file_size / 1024, 2) }} KB
                    </small>
                </li>
            @endforeach
        </ul>
    @endif

    <div class="footer">
        <p>Este documento fue generado automáticamente por el Sistema de Evaluación de Proyectos.</p>
        <p>Fecha: {{ now()->format('d/m/Y H:i') }}</p>
    </div>
</body>
</html>
