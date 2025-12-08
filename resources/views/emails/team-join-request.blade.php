<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitud para unirse al equipo</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
            padding: 40px 20px;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }
        .email-header {
            background: linear-gradient(135deg, #4c1d95, #7c3aed);
            padding: 40px 30px;
            text-align: center;
            color: #ffffff;
        }
        .email-header h1 {
            margin: 0 0 10px 0;
            font-size: 28px;
            font-weight: 700;
        }
        .email-header p {
            margin: 0;
            font-size: 16px;
            opacity: 0.9;
        }
        .email-body {
            padding: 40px 30px;
            color: #1f2937;
        }
        .info-box {
            background: linear-gradient(135deg, #f3f4f6, #e5e7eb);
            border-left: 4px solid #7c3aed;
            padding: 20px;
            border-radius: 8px;
            margin: 25px 0;
        }
        .info-box p {
            margin: 8px 0;
            font-size: 15px;
        }
        .info-box strong {
            color: #4c1d95;
            font-weight: 600;
        }
        .role-badge {
            display: inline-block;
            background: linear-gradient(135deg, #4c1d95, #7c3aed);
            color: #ffffff;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            margin-top: 5px;
        }
        .message {
            background: #f9fafb;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
            font-size: 15px;
            line-height: 1.6;
            color: #374151;
        }
        .action-buttons {
            text-align: center;
            margin: 30px 0;
        }
        .btn {
            display: inline-block;
            padding: 14px 32px;
            margin: 8px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        .btn-primary {
            background: linear-gradient(135deg, #4c1d95, #7c3aed);
            color: #ffffff;
            box-shadow: 0 4px 15px rgba(124, 58, 237, 0.4);
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(124, 58, 237, 0.6);
        }
        .email-footer {
            background: #f3f4f6;
            padding: 25px 30px;
            text-align: center;
            color: #6b7280;
            font-size: 13px;
            border-top: 1px solid #e5e7eb;
        }
        .icon {
            font-size: 48px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <div class="icon">👥</div>
            <h1>Nueva Solicitud de Ingreso</h1>
            <p>Alguien quiere unirse a tu equipo</p>
        </div>

        <!-- Body -->
        <div class="email-body">
            <p style="font-size: 16px; margin-bottom: 20px;">
                Hola, <strong>{{ $team->leader->name ?? 'Líder' }}</strong>
            </p>

            <p style="font-size: 15px; color: #4b5563; line-height: 1.6;">
                Has recibido una nueva solicitud para unirse a tu equipo <strong>{{ $team->name }}</strong>.
            </p>

            <!-- Info Box -->
            <div class="info-box">
                <p><strong>📋 Información del solicitante:</strong></p>
                <p><strong>Nombre:</strong> {{ $applicant->name }}</p>
                <p><strong>Email:</strong> {{ $applicant->email }}</p>
                <p><strong>Rol solicitado:</strong> 
                    <span class="role-badge">
                        @if($role === 'Back')
                            💻 Back-end
                        @elseif($role === 'Front')
                            🎨 Front-end
                        @else
                            ✏️ Diseñador
                        @endif
                    </span>
                </p>
            </div>

            <!-- Message -->
            <div class="message">
                <strong>💡 Recuerda:</strong>
                <ul style="margin: 10px 0; padding-left: 20px;">
                    <li>Tu equipo puede tener máximo 4 miembros (incluyéndote)</li>
                    <li>Cada rol (Back-end, Front-end, Diseñador) solo puede ser ocupado por una persona</li>
                    <li>Revisa que el rol solicitado esté disponible antes de aceptar</li>
                </ul>
            </div>

            <!-- Action Button -->
            <div class="action-buttons">
                <a href="{{ url('/panel/mi-equipo') }}" class="btn btn-primary">
                    Ver solicitud en el panel
                </a>
            </div>

            <p style="font-size: 14px; color: #6b7280; margin-top: 25px; text-align: center;">
                Puedes aceptar o rechazar esta solicitud desde tu panel de equipo.
            </p>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p style="margin: 0 0 10px 0;">
                <strong>Sistema de Gestión de Hackathons</strong>
            </p>
            <p style="margin: 0;">
                Este es un correo automático, por favor no respondas a este mensaje.
            </p>
        </div>
    </div>
</body>
</html>
