<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invitación al Equipo</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
            color: white;
            padding: 40px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }
        .content {
            padding: 40px 30px;
        }
        .content h2 {
            color: #1f2937;
            font-size: 20px;
            margin-top: 0;
            margin-bottom: 15px;
        }
        .content p {
            color: #4b5563;
            line-height: 1.6;
            margin: 10px 0;
            font-size: 16px;
        }
        .team-info {
            background-color: #f3f4f6;
            border-left: 4px solid #4f46e5;
            padding: 15px 20px;
            margin: 25px 0;
            border-radius: 6px;
        }
        .team-info p {
            margin: 8px 0;
            color: #1f2937;
            font-weight: 500;
        }
        .inviter-name {
            color: #4f46e5;
            font-weight: 600;
        }
        .team-name {
            color: #4f46e5;
            font-weight: 600;
            font-size: 18px;
        }
        .button-container {
            text-align: center;
            margin: 35px 0;
        }
        .button {
            display: inline-block;
            padding: 15px 40px;
            background-color: #4f46e5;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        .button:hover {
            background-color: #4338ca;
        }
        .footer {
            background-color: #f9fafb;
            padding: 20px 30px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            color: #6b7280;
            font-size: 12px;
        }
        .link-note {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 12px 15px;
            margin: 20px 0;
            border-radius: 4px;
            font-size: 13px;
            color: #92400e;
        }
        .link-note a {
            color: #4f46e5;
            text-decoration: none;
            word-break: break-all;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>¡Bienvenido!</h1>
            <p style="margin: 10px 0 0 0; font-size: 14px; opacity: 0.9;">Has sido invitado a unirte a un equipo</p>
        </div>

        <div class="content">
            <h2>Hola,</h2>
            
            <p><span class="inviter-name">{{ $invitation->inviter->name }}</span> te ha invitado a unirte al equipo:</p>

            <div class="team-info">
                <p><strong>Equipo:</strong> <span class="team-name">{{ $invitation->team->name }}</span></p>
                @if($invitation->role)
                <p><strong>Rol asignado:</strong> <span class="team-name">{{ $invitation->role }}</span></p>
                @endif
            </div>

            <p>Haz clic en el botón de abajo para aceptar la invitación y unirte al equipo:</p>

            <div class="button-container">
                <a href="{{ $acceptUrl }}" class="button">Aceptar Invitación</a>
            </div>

            <p>Si el botón anterior no funciona, copia y pega el siguiente enlace en tu navegador:</p>
            
            <div class="link-note">
                <a href="{{ $acceptUrl }}">{{ $acceptUrl }}</a>
            </div>

            <p style="color: #6b7280; font-size: 14px; margin-top: 30px;">
                <strong>Nota:</strong> Si no tienes cuenta aún, deberás crear una con el correo <strong>{{ $invitation->email }}</strong> para aceptar la invitación.
            </p>
        </div>

        <div class="footer">
            <p style="margin: 0;">Este es un correo automático. Por favor, no respondas a este mensaje.</p>
            <p style="margin: 5px 0 0 0;">© {{ date('Y') }} - Sistema de Gestión de Equipos</p>
        </div>
    </div>
</body>
</html>
