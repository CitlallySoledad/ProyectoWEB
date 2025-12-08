<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperación de Contraseña</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f3f4f6;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f3f4f6; padding: 40px 20px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 16px; overflow: hidden; box-shadow: 0 20px 50px rgba(0,0,0,0.3);">
                    
                    <!-- Encabezado -->
                    <tr>
                        <td style="padding: 40px 40px 30px; text-align: center;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: 700; letter-spacing: -0.5px;">
                                🔐 Recuperación de Contraseña
                            </h1>
                        </td>
                    </tr>

                    <!-- Contenido -->
                    <tr>
                        <td style="background-color: #ffffff; padding: 40px;">
                            <p style="margin: 0 0 20px; color: #1f2937; font-size: 16px; line-height: 1.6;">
                                Hola <strong>{{ $user->name }}</strong>,
                            </p>
                            
                            <p style="margin: 0 0 20px; color: #4b5563; font-size: 15px; line-height: 1.6;">
                                Recibimos una solicitud para restablecer la contraseña de tu cuenta en la plataforma de Hackathon.
                            </p>

                            <p style="margin: 0 0 30px; color: #4b5563; font-size: 15px; line-height: 1.6;">
                                Para crear una nueva contraseña, haz clic en el siguiente botón:
                            </p>

                            <!-- Botón -->
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center" style="padding: 10px 0 30px;">
                                        <a href="{{ $resetUrl }}" 
                                           style="display: inline-block; padding: 16px 40px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #ffffff; text-decoration: none; border-radius: 50px; font-weight: 600; font-size: 16px; box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);">
                                            Restablecer Contraseña
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <!-- Información adicional -->
                            <div style="background-color: #fef3c7; border-left: 4px solid #f59e0b; padding: 16px; border-radius: 8px; margin-bottom: 25px;">
                                <p style="margin: 0 0 10px; color: #92400e; font-size: 14px; font-weight: 600;">
                                    ⚠️ Importante:
                                </p>
                                <p style="margin: 0; color: #78350f; font-size: 14px; line-height: 1.5;">
                                    Este enlace expirará en <strong>60 minutos</strong>. Si no solicitaste restablecer tu contraseña, ignora este correo y tu contraseña permanecerá sin cambios.
                                </p>
                            </div>

                            <!-- Enlace alternativo -->
                            <div style="background-color: #f9fafb; border-radius: 8px; padding: 20px; margin-bottom: 20px;">
                                <p style="margin: 0 0 10px; color: #6b7280; font-size: 13px;">
                                    Si el botón no funciona, copia y pega este enlace en tu navegador:
                                </p>
                                <p style="margin: 0; word-break: break-all;">
                                    <a href="{{ $resetUrl }}" style="color: #667eea; font-size: 13px; text-decoration: none;">
                                        {{ $resetUrl }}
                                    </a>
                                </p>
                            </div>

                            <p style="margin: 0; color: #6b7280; font-size: 14px; line-height: 1.6;">
                                Si tienes algún problema, contáctanos respondiendo a este correo.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #1f2937; padding: 30px 40px; text-align: center;">
                            <p style="margin: 0 0 10px; color: #d1d5db; font-size: 14px;">
                                © {{ date('Y') }} Hackathon Platform
                            </p>
                            <p style="margin: 0; color: #9ca3af; font-size: 12px;">
                                Este es un correo automático, por favor no respondas a esta dirección.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
