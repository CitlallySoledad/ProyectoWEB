<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Constancia de Reconocimiento</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .email-container {
            background: white;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #667eea;
        }
        .trophy {
            font-size: 64px;
            margin-bottom: 10px;
        }
        .title {
            font-size: 28px;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
        }
        .content {
            margin: 30px 0;
        }
        .highlight {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: center;
        }
        .place-badge {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 10px;
        }
        .info-item {
            margin: 15px 0;
            padding: 12px;
            background: #f8fafc;
            border-radius: 6px;
            border-left: 4px solid #667eea;
        }
        .info-label {
            font-weight: 600;
            color: #475569;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .info-value {
            font-size: 18px;
            color: #1e293b;
            margin-top: 4px;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e2e8f0;
            text-align: center;
            color: #64748b;
            font-size: 14px;
        }
        .attachment-notice {
            background: #fff7ed;
            border: 2px solid #fb923c;
            border-radius: 8px;
            padding: 16px;
            margin: 20px 0;
            text-align: center;
        }
        .attachment-icon {
            font-size: 32px;
            margin-bottom: 8px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="trophy">🏆</div>
            <h1 class="title">¡Felicidades!</h1>
        </div>

        <div class="content">
            <p style="font-size: 16px;">Hola <strong>{{ $memberName }}</strong>,</p>

            <p style="font-size: 16px;">
                Nos complace informarte que tu equipo <strong>{{ $teamName }}</strong> ha obtenido una posición destacada en el evento:
            </p>

            <div class="highlight">
                <div class="place-badge">
                    @if($place == 1)
                        🥇 1er Lugar
                    @elseif($place == 2)
                        🥈 2do Lugar
                    @else
                        🥉 3er Lugar
                    @endif
                </div>
                <div style="font-size: 18px; font-weight: 600;">{{ $eventName }}</div>
            </div>

            <div class="info-item">
                <div class="info-label">Equipo</div>
                <div class="info-value">{{ $teamName }}</div>
            </div>

            <div class="info-item">
                <div class="info-label">Evento</div>
                <div class="info-value">{{ $eventName }}</div>
            </div>

            <div class="info-item">
                <div class="info-label">Posición</div>
                <div class="info-value">
                    @if($place == 1)
                        Primer Lugar 🥇
                    @elseif($place == 2)
                        Segundo Lugar 🥈
                    @else
                        Tercer Lugar 🥉
                    @endif
                </div>
            </div>

            <div class="attachment-notice">
                <div class="attachment-icon">📎</div>
                <strong>Tu constancia está adjunta a este correo</strong>
                <p style="margin: 8px 0 0 0; font-size: 14px; color: #92400e;">
                    Por favor descarga el archivo PDF adjunto para conservar tu reconocimiento oficial.
                </p>
            </div>

            <p style="font-size: 16px; margin-top: 30px;">
                Agradecemos tu participación y dedicación. Este logro es el resultado del esfuerzo conjunto de todo el equipo.
            </p>

            <p style="font-size: 16px;">
                <strong>¡Sigue así y mucho éxito en tus futuros proyectos!</strong>
            </p>
        </div>

        <div class="footer">
            <p style="margin: 0;">Este es un correo automático, por favor no respondas a este mensaje.</p>
            <p style="margin: 10px 0 0 0;">© {{ date('Y') }} Sistema de Gestión de Hackathones</p>
        </div>
    </div>
</body>
</html>
