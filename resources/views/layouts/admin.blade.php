<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Admin')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Iconos (Bootstrap Icons) --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <style>
        body {
            min-height: 100vh;
            margin: 0;
            background: #f2f2f2;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        /* LOGIN */
        .login-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f5f5f5;
        }

        .login-card-custom {
            width: 900px;
            max-width: 95%;
            border: none;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }

        .login-left {
            background: linear-gradient(135deg, #ff0066, #8b00ff, #00bfff);
            padding: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-left img {
            max-width: 100%;
            height: auto;
        }

        .login-right {
            padding: 40px 50px;
        }

        .login-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .login-subtitle {
            color: #777;
            margin-bottom: 30px;
        }

        .login-input {
            border-radius: 999px;
            padding-left: 20px;
            padding-right: 20px;
        }

        .login-btn {
            border-radius: 999px;
            width: 100%;
        }

        /* DASHBOARD */
        .dashboard-wrapper {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background: linear-gradient(135deg, #0b1633, #10285a);
            color: #fff;
        }

        .dashboard-topbar {
            background: rgba(3, 7, 30, 0.95);
            padding: 12px 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .dashboard-topbar-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .dashboard-logo {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            overflow: hidden;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            color: #000;
            font-weight: bold;
        }

        .dashboard-title {
            font-size: 1rem;
            font-weight: 600;
        }

        .dashboard-user-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #111827;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .dashboard-content {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }

        .dashboard-cards {
            display: flex;
            gap: 60px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .dashboard-card {
            width: 180px;
            height: 190px;
            border-radius: 20px;
            background: rgba(15, 23, 42, 0.9);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 15px;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s, background 0.2s;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.35);
            background: rgba(37, 99, 235, 0.95);
        }

        .dashboard-card-icon {
            font-size: 2.2rem;
        }

        .dashboard-card-title {
            font-size: 0.95rem;
            font-weight: 500;
        }

        /* PÁGINAS DE LISTADO (EVENTOS / EQUIPOS / EVALUACIONES) */
        .admin-page-wrapper {
            min-height: 100vh;
            height: 100vh;
            width: 100vw;
            display: flex;
            align-items: stretch;
            justify-content: stretch;
            padding: 0;
            background: radial-gradient(circle at top left, #1d2b64, #090a0f);
            color: #fff;
        }

        .admin-page-card {
            flex: 1;
            height: 100%;
            width: 100%;
            border-radius: 0;
            /* sin margen, ocupa toda la pantalla */
            background: linear-gradient(135deg, #111827, #1f2937, #111827);
            box-shadow: none;
            /* ya no hace falta sombra */
            display: flex;
            overflow: hidden;
        }



        .admin-sidebar {
            width: 90px;
            height: 100%;
            background: linear-gradient(180deg, #a855f7, #3b82f6);
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 30px 0;
            border-radius: 0;
            /* pega al borde izquierdo */
        }

        .admin-sidebar-back {
            width: 40px;
            height: 40px;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, 0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            text-decoration: none;
            margin-bottom: 40px;
        }

        .admin-sidebar-icon {
            width: 40px;
            height: 40px;
            border-radius: 999px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 24px;
            color: #18181b;
        }

        .admin-sidebar-icon.active {
            background: rgba(15, 23, 42, 0.3);
            color: #fff;
        }

        .admin-page-main {
            flex: 1;
            padding: 32px 40px 40px 40px;
            position: relative;
            height: 100%;
            overflow-y: auto;
            /* scroll interno si el contenido es largo */
        }

        .admin-page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .admin-page-title {
            font-size: 1.8rem;
            font-weight: 600;
        }

        .admin-page-user {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 14px;
            border-radius: 999px;
            background: rgba(37, 99, 235, 0.9);
            font-size: 0.9rem;
        }

        .admin-page-search-row {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 30px;
            max-width: 100%;
        }

        .admin-page-search-input-wrapper {
            flex: 1;
            background: #f9fafb;
            border-radius: 999px;
            display: flex;
            align-items: center;
            padding: 4px 16px;
        }

        .admin-page-search-input-wrapper input {
            border: none;
            outline: none;
            background: transparent;
            width: 100%;
        }

        .admin-page-create-btn {
            border-radius: 999px;
            border: none;
            padding: 8px 18px;
            font-size: 0.9rem;
            background: #000;
            color: #fff;
        }

        .admin-list {
            display: flex;
            flex-direction: column;
            gap: 14px;
            max-width: 100%;
        }
        

        .admin-list-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: rgba(37, 99, 235, 0.5);
            padding: 10px 18px;
            border-radius: 999px;
        }

        .admin-list-item-title {
            font-size: 0.95rem;
        }

        .admin-list-item-right {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.85rem;
        }

        .admin-list-edit-btn {
            padding: 6px 18px;
            border-radius: 999px;
            border: none;
            background: #60a5fa;
            color: #111827;
            font-weight: 500;
            font-size: 0.85rem;
        }

        /* FORMULARIOS CREAR / EDITAR */
        .admin-form-title {
            font-size: 1.9rem;
            font-weight: 600;
            text-align: center;
            margin-bottom: 26px;
            color: #9ca3af;
        }

        .admin-form-label {
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .admin-form-input,
        .admin-form-select,
        .admin-form-textarea {
            width: 100%;
            border-radius: 999px;
            border: none;
            outline: none;
            padding: 8px 18px;
            background: rgba(37, 99, 235, 0.25);
            color: #e5e7eb;
            font-size: 0.9rem;
        }

        .admin-form-textarea {
            border-radius: 18px;
            resize: none;
            min-height: 70px;
        }

        .admin-form-input::placeholder,
        .admin-form-select::placeholder,
        .admin-form-textarea::placeholder {
            color: #9ca3af;
        }

        .admin-form-row {
            margin-bottom: 14px;
        }

        .admin-form-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 24px;
        }

        .admin-form-col {
            flex: 1;
            min-width: 260px;
        }

        .admin-form-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 28px;
        }

        .admin-btn-pill {
            border-radius: 999px;
            padding: 8px 24px;
            border: none;
            font-size: 0.9rem;
        }

        .admin-btn-secondary {
            background: rgba(148, 163, 184, 0.35);
            color: #e5e7eb;
        }

        .admin-btn-primary {
            background: #60a5fa;
            color: #111827;
            font-weight: 600;
        }

        .admin-btn-disabled {
            background: rgba(148, 163, 184, 0.25);
            color: #9ca3af;
        }

        .admin-team-members-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 6px;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .admin-team-member-row {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 10px;
        }

        .admin-team-member-name {
            flex: 1;
        }

        .admin-team-member-role {
            width: 140px;
            display: flex;
            align-items: center;
        }

        .admin-role-label {
            border-radius: 999px;
            padding: 4px 14px;
            background: #ef4444;
            color: #fff;
            font-size: 0.75rem;
            text-align: center;
            width: 100%;
        }

        .admin-form-select-small {
            border-radius: 999px;
            border: none;
            outline: none;
            padding: 4px 12px;
            font-size: 0.8rem;
            background: #60a5fa;
            color: #111827;
            width: 100%;
        }

        /* Contenido interno centrado y con buen margen */
        .admin-page-inner {
            max-width: 1200px;
            /* ancho máximo del contenido */
            margin: 0 auto;
            /* centrar horizontal */
            height: 100%;
            display: flex;
            flex-direction: column;
        }


        /* PANEL DE EVALUACIONES */
        .eval-header-row {
            display: flex;
            max-width: 100%;
            justify-content: space-between;
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 10px;
            color: #e5e7eb;
        }

        .eval-header-col {
            flex: 1;
            text-align: center;
        }

        .eval-header-col:first-child {
            text-align: left;
        }

        .eval-row {
            display: flex;
            align-items: center;
            background: rgba(37, 99, 235, 0.5);
            border-radius: 999px;
            padding: 10px 18px;
            margin-bottom: 12px;
            max-width: 100%;
        }

        .eval-row-name {
            flex: 2;
            font-weight: 600;
        }

        .eval-row-metric {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .eval-progress-track {
            width: 100%;
            height: 8px;
            border-radius: 999px;
            background: #e5e7eb;
            overflow: hidden;
        }

        .eval-progress-fill {
            height: 100%;
            background: #1d4ed8;
        }

        .eval-score-number {
            width: 40px;
            text-align: center;
            font-size: 0.85rem;
        }
    </style>

    @stack('styles')
</head>

<body>
    @yield('content')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>
