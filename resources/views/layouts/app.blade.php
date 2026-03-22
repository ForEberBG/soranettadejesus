<!-- layouts/app.blade.php -->
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'IE Sor Annetta de Jesús - Control de Aula')</title>
    <link href="{{ asset('volt/assets/css/volt.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Merriweather+Sans:wght@700;800&family=Nunito:wght@400;600;700&display=swap"
        rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        :root {
            --verde-oscuro: #1a5c2e;
            --verde-medio: #236b38;
            --verde-claro: #2d8a48;
            --verde-suave: #e8f5ec;
            --dorado: #c8991a;
            --dorado-claro: #f0c040;
            --dorado-suave: #fdf6e0;
            --blanco: #ffffff;
            --gris-bg: #f5f7f5;
            --gris-borde: #dde8df;
            --sidebar-bg: rgba(20, 60, 30, 0.97);
            --navbar-bg: linear-gradient(90deg, #1a5c2e 0%, #2d8a48 100%);
            --card-bg: rgba(255, 255, 255, 0.95);
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            overflow-x: hidden;
            height: 100%;
            font-family: 'Nunito', sans-serif;
            background:
                radial-gradient(ellipse at 20% 50%, rgba(45, 138, 72, 0.1) 0%, transparent 60%),
                radial-gradient(ellipse at 80% 20%, rgba(26, 92, 46, 0.6) 0%, transparent 50%),
                linear-gradient(180deg, #0d2e15 0%, #122a18 30%, #1a3d22 60%, #143320 100%);
            background-attachment: fixed;
            background-size: cover;
        }

        body::after {
            content: '';
            position: fixed;
            inset: 0;
            background-image:
                radial-gradient(circle at 15% 25%, rgba(200, 153, 26, 0.05) 0%, transparent 25%),
                radial-gradient(circle at 85% 75%, rgba(45, 138, 72, 0.04) 0%, transparent 20%);
            pointer-events: none;
            z-index: 0;
        }

        /* ── SIDEBAR ── */
        .sidebar {
            width: 255px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            z-index: 1040;
            transition: width 0.3s ease;
            overflow: hidden;
            background: var(--sidebar-bg);
            border-right: 2px solid rgba(200, 153, 26, 0.4);
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.4);
        }

        .sidebar.collapsed {
            width: 72px;
        }

        .sidebar.collapsed .sidebar-text {
            display: none !important;
        }

        .sidebar.collapsed .sidebar-logo-text {
            display: none !important;
        }

        .sidebar-inner {
            height: 100%;
            display: flex;
            flex-direction: column;
            padding: 0;
            overflow-y: auto;
            scrollbar-width: none;
        }

        .sidebar-inner::-webkit-scrollbar {
            display: none;
        }

        /* Brand */
        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 16px 16px 14px;
            text-decoration: none;
            border-bottom: 1px solid rgba(200, 153, 26, 0.25);
            background: linear-gradient(135deg, rgba(200, 153, 26, 0.12), transparent);
        }

        .sidebar-brand img {
            width: 46px;
            height: 46px;
            border-radius: 8px;
            border: 2px solid var(--dorado);
            object-fit: contain;
            flex-shrink: 0;
            background: rgba(255, 255, 255, 0.1);
            padding: 2px;
        }

        .sidebar-logo-text {
            display: flex;
            flex-direction: column;
            line-height: 1.2;
        }

        .sidebar-logo-text .brand-name {
            font-family: 'Merriweather Sans', sans-serif;
            font-size: 0.82rem;
            font-weight: 800;
            color: var(--dorado-claro);
            letter-spacing: 0.3px;
        }

        .sidebar-logo-text .brand-sub {
            font-size: 0.62rem;
            color: rgba(255, 255, 255, 0.5);
            text-transform: uppercase;
            letter-spacing: 1.2px;
            margin-top: 2px;
        }

        /* Aula badge */
        .sidebar-aula {
            margin: 10px 12px;
            background: rgba(200, 153, 26, 0.1);
            border: 1px solid rgba(200, 153, 26, 0.25);
            border-radius: 8px;
            padding: 8px 12px;
        }

        .sidebar-aula .aula-label {
            font-size: 0.6rem;
            color: rgba(255, 255, 255, 0.4);
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        .sidebar-aula .aula-value {
            font-size: 0.82rem;
            font-weight: 700;
            color: var(--dorado-claro);
            margin-top: 1px;
        }

        /* Nav items */
        .sidebar-nav {
            padding: 8px 10px;
            flex: 1;
            list-style: none;
            margin: 0;
        }

        .sidebar-nav .nav-item {
            margin-bottom: 2px;
        }

        .sidebar-nav .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            border-radius: 8px;
            color: rgba(255, 255, 255, 0.72);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 600;
            transition: all 0.2s ease;
            border: 1px solid transparent;
            white-space: nowrap;
        }

        .sidebar-nav .nav-link i {
            width: 18px;
            text-align: center;
            font-size: 0.88rem;
            color: var(--dorado-claro);
            flex-shrink: 0;
        }

        .sidebar-nav .nav-link:hover {
            background: rgba(200, 153, 26, 0.12);
            border-color: rgba(200, 153, 26, 0.3);
            color: var(--blanco);
        }

        .sidebar-nav .nav-link.active {
            background: linear-gradient(135deg, rgba(200, 153, 26, 0.2), rgba(200, 153, 26, 0.08));
            border-color: var(--dorado);
            color: var(--dorado-claro);
            box-shadow: 0 2px 8px rgba(200, 153, 26, 0.15);
        }

        .sidebar-nav .nav-link.active i {
            color: var(--dorado-claro);
        }

        .nav-section-title {
            font-size: 0.6rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: rgba(255, 255, 255, 0.3);
            padding: 10px 12px 4px;
            font-weight: 700;
        }

        /* Logout */
        .btn-logout {
            margin: 8px 10px 16px;
            padding: 9px 12px;
            border-radius: 8px;
            background: rgba(192, 57, 43, 0.15);
            border: 1px solid rgba(192, 57, 43, 0.4);
            color: #ff6b6b;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 10px;
            text-align: left;
            width: calc(100% - 20px);
        }

        .btn-logout:hover {
            background: rgba(192, 57, 43, 0.35);
            border-color: #c0392b;
            color: #fff;
        }

        /* ── MAIN ── */
        .main-content {
            margin-left: 255px;
            transition: margin-left 0.3s ease;
            min-height: 100vh;
        }

        body.sidebar-collapsed .main-content {
            margin-left: 72px;
        }

        /* Topbar */
        .top-navbar {
            background: var(--navbar-bg);
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.3);
            border-bottom: 2px solid rgba(200, 153, 26, 0.5);
            position: sticky;
            top: 0;
            z-index: 1030;
        }

        .top-navbar .brand-text {
            font-family: 'Merriweather Sans', sans-serif;
            font-size: 0.95rem;
            font-weight: 800;
            color: var(--blanco);
            letter-spacing: 0.3px;
        }

        .top-navbar .brand-text span {
            color: var(--dorado-claro);
        }

        .navbar-toggle {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            padding: 6px 10px;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .navbar-toggle:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .navbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-badge {
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(200, 153, 26, 0.4);
            border-radius: 20px;
            padding: 4px 14px 4px 6px;
        }

        .user-avatar {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: var(--dorado);
            border: 2px solid var(--dorado-claro);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 800;
            color: var(--verde-oscuro);
        }

        .user-badge span {
            color: white;
            font-size: 0.8rem;
            font-weight: 600;
        }

        /* Content */
        .page-content {
            padding: 24px;
            min-height: calc(100vh - 60px);
        }

        .card {
            background: var(--card-bg) !important;
            border-radius: 12px !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12) !important;
        }

        .card-header {
            border-radius: 12px 12px 0 0 !important;
        }

        .table-dark th {
            background: var(--verde-oscuro) !important;
        }

        .badge {
            font-size: 0.75rem;
            padding: 5px 10px;
            border-radius: 20px;
        }

        .btn-primary {
            background: var(--verde-oscuro) !important;
            border-color: var(--verde-oscuro) !important;
        }

        .btn-primary:hover {
            background: var(--verde-claro) !important;
            border-color: var(--verde-claro) !important;
        }

        h1,
        h2,
        h3 {
            font-family: 'Merriweather Sans', sans-serif;
        }

        @media (max-width: 767.98px) {
            .sidebar {
                display: none !important;
            }

            .main-content {
                margin-left: 0 !important;
            }
        }

        #mobileSidebarOverlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            z-index: 1039;
            display: none;
            backdrop-filter: blur(2px);
        }

        #mobileSidebarMenu {
            position: fixed;
            top: 0;
            left: 0;
            width: 260px;
            height: 100vh;
            background: var(--sidebar-bg);
            z-index: 1040;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.4);
            padding: 0;
            display: none;
            overflow-y: auto;
            border-right: 2px solid rgba(200, 153, 26, 0.4);
        }

        #mobileSidebarMenu .nav-link {
            color: rgba(255, 255, 255, 0.75);
            border-radius: 8px;
            padding: 9px 12px;
            font-weight: 600;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.2s;
            border: 1px solid transparent;
        }

        #mobileSidebarMenu .nav-link i {
            color: var(--dorado-claro);
            width: 18px;
            text-align: center;
        }

        #mobileSidebarMenu .nav-link:hover,
        #mobileSidebarMenu .nav-link.active {
            background: rgba(200, 153, 26, 0.15);
            border-color: rgba(200, 153, 26, 0.35);
            color: white;
        }

        #modal-portal {
            position: fixed;
            inset: 0;
            z-index: 99999;
            pointer-events: none;
        }

        #modal-portal>* {
            pointer-events: all;
        }
    </style>
    @stack('css')
</head>

<body>
    @php
    $userRoles = auth()->user()->roles->pluck('name');
    $esAdmin = $userRoles->contains('ADMINISTRADOR');
    $esMozo = $userRoles->contains('mozo');
    $esChef = $userRoles->contains('chef');
    $config = App\Models\Configuracion::first();
    $iniciales = strtoupper(substr(auth()->user()->name ?? 'A', 0, 1));
    @endphp

    <!-- ── SIDEBAR DESKTOP ── -->
    <nav id="sidebarMenu" class="sidebar d-none d-md-flex flex-column">
        <div class="sidebar-inner">

            {{-- Logo y nombre del colegio --}}
            <a href="{{ $esAdmin ? route('admin.index') : '#' }}" class="sidebar-brand">
                @if($config && $config->logo)
                <img src="{{ asset($config->logo) }}" alt="Logo IE Sor Annetta">
                @else
                <img src="{{ asset('images/logo.png') }}" alt="Logo IE Sor Annetta"
                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                <div
                    style="display:none;width:46px;height:46px;border-radius:8px;background:rgba(200,153,26,0.2);align-items:center;justify-content:center;flex-shrink:0;border:2px solid var(--dorado)">
                    <i class="fas fa-graduation-cap" style="color:var(--dorado-claro);font-size:1.2rem"></i>
                </div>
                @endif
                <div class="sidebar-logo-text">
                    <span class="brand-name">Sor Annetta<br>de Jesús</span>
                    <span class="brand-sub">Control de Aula</span>
                </div>
            </a>

            {{-- Aula activa --}}
            <div class="sidebar-aula sidebar-text">
                <div class="aula-label">Aula activa</div>
                <div class="aula-value">{{ $config->aula ?? '4to "C"' }} - {{ $config->anio_escolar ?? date('Y') }}
                </div>
            </div>

            <ul class="sidebar-nav">
                @if($esAdmin)

                {{-- MÓDULOS DEL COLEGIO --}}
                <li class="nav-section-title sidebar-text">Control de Aula</li>
                <li class="nav-item">
                    <a href="{{ route('admin.index') }}" class="nav-link {{ request()->is('admin') ? 'active' : '' }}">
                        <i class="fas fa-home"></i><span class="sidebar-text">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('admin/alumnos') }}"
                        class="nav-link {{ request()->is('admin/alumnos*') ? 'active' : '' }}">
                        <i class="fas fa-user-graduate"></i><span class="sidebar-text">Alumnos</span>
                    </a>
                </li>

                <li class="nav-section-title sidebar-text">Gestión Económica</li>
                <li class="nav-item">
                    <a href="{{ url('admin/actividades') }}"
                        class="nav-link {{ request()->is('admin/actividades*') ? 'active' : '' }}">
                        <i class="fas fa-calendar-alt"></i><span class="sidebar-text">Actividades</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('admin/cuotas') }}"
                        class="nav-link {{ request()->is('admin/cuotas*') ? 'active' : '' }}">
                        <i class="fas fa-hand-holding-usd"></i><span class="sidebar-text">Cuotas y Cobros</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('admin/gastos') }}"
                        class="nav-link {{ request()->is('admin/gastos*') ? 'active' : '' }}">
                        <i class="fas fa-receipt"></i><span class="sidebar-text">Gastos</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('caja.index') }}"
                        class="nav-link {{ request()->is('admin/caja-chica*') ? 'active' : '' }}">
                        <i class="fas fa-cash-register"></i><span class="sidebar-text">Caja Chica</span>
                    </a>
                </li>

                <li class="nav-section-title sidebar-text">Organización</li>
                <li class="nav-item">
                    <a href="{{ url('admin/reuniones') }}"
                        class="nav-link {{ request()->is('admin/reuniones*') ? 'active' : '' }}">
                        <i class="fas fa-handshake"></i><span class="sidebar-text">Reuniones</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('admin/reportes') }}"
                        class="nav-link {{ request()->is('admin/reportes*') ? 'active' : '' }}">
                        <i class="fas fa-chart-bar"></i><span class="sidebar-text">Reportes</span>
                    </a>
                </li>

                <li class="nav-section-title sidebar-text">Sistema</li>
                <li class="nav-item">
                    <a href="{{ route('usuarios.aula') }}"
                        class="nav-link {{ request()->is('admin/usuarios-aula*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i><span class="sidebar-text">Usuarios</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('configuracion.aula') }}"
                        class="nav-link {{ request()->routeIs('configuracion.aula') ? 'active' : '' }}">
                        <i class="fas fa-cogs"></i><span class="sidebar-text">Configuración</span>
                    </a>
                </li>
                @elseif(auth()->user()->rol === 'padre')
                <li class="nav-section-title sidebar-text">Mi Portal</li>
                <li class="nav-item">
                    <a href="{{ route('portal.padres') }}"
                        class="nav-link {{ request()->is('admin/portal-padres*') ? 'active' : '' }}">
                        <i class="fas fa-home"></i><span class="sidebar-text">Estado de pagos</span>
                    </a>
                </li>
                @else
                <li class="nav-item">
                    <a href="{{ route('admin.index') }}" class="nav-link">
                        <i class="fas fa-home"></i><span class="sidebar-text">Inicio</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('usuarios.aula') }}"
                        class="nav-link {{ request()->is('admin/usuarios-aula*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i><span class="sidebar-text">Usuarios</span>
                    </a>
                </li>
                @endif
            </ul>
            <button class="btn-logout" onclick="confirmLogout()">
                <i class="fas fa-sign-out-alt" style="color:#ff6b6b;width:18px;text-align:center"></i>
                <span class="sidebar-text">Cerrar sesión</span>
            </button>
        </div>
        <div style="padding:10px 14px; font-size:0.7rem; border-top:1px solid rgba(255,255,255,0.1); margin-top:8px">
            <span style="color:#f0c040; font-weight:700">Desarrollado por:</span>
            <span style="color:rgba(255,255,255,0.85)"> Ing. Eber Bedoya</span>
        </div>
    </nav>

    <!-- ── MOBILE OVERLAY ── -->
    <div id="mobileSidebarOverlay" onclick="closeMobileSidebar()"></div>
    <div id="mobileSidebarMenu">
        <div
            style="padding:16px;border-bottom:1px solid rgba(200,153,26,0.3);display:flex;align-items:center;justify-content:space-between">
            <span
                style="font-family:'Merriweather Sans',sans-serif;color:var(--dorado-claro);font-weight:800;font-size:0.9rem">
                IE Sor Annetta
            </span>
            <button class="btn btn-sm" style="background:rgba(255,255,255,0.1);border:none;color:white"
                onclick="closeMobileSidebar()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <ul class="nav nav-pills flex-column p-2">
            <li class="nav-item mb-1"><a href="{{ route('admin.index') }}" class="nav-link"><i class="fas fa-home"></i>
                    Dashboard</a></li>
            <li class="nav-item mb-1"><a href="{{ url('admin/alumnos') }}" class="nav-link"><i
                        class="fas fa-user-graduate"></i> Alumnos</a></li>
            <li class="nav-item mb-1"><a href="{{ url('admin/actividades') }}" class="nav-link"><i
                        class="fas fa-calendar-alt"></i> Actividades</a></li>
            <li class="nav-item mb-1"><a href="{{ url('admin/cuotas') }}" class="nav-link"><i
                        class="fas fa-hand-holding-usd"></i> Cuotas y Cobros</a></li>
            <li class="nav-item mb-1"><a href="{{ url('admin/gastos') }}" class="nav-link"><i
                        class="fas fa-receipt"></i> Gastos</a></li>
            <li class="nav-item mb-1"><a href="{{ url('admin/reuniones') }}" class="nav-link"><i
                        class="fas fa-handshake"></i> Reuniones</a></li>
            <li class="nav-item mb-1"><a href="{{ url('admin/reportes') }}" class="nav-link"><i
                        class="fas fa-chart-bar"></i> Reportes</a></li>
            <li class="nav-item mt-3">
                <button class="btn-logout w-100" onclick="confirmLogout()">
                    <i class="fas fa-sign-out-alt"></i> Cerrar sesión
                </button>
            </li>
        </ul>
    </div>

    <!-- ── MAIN CONTENT ── -->
    <div class="main-content">
        <nav class="top-navbar">
            <div class="d-flex align-items-center gap-2">
                <button class="navbar-toggle d-none d-md-block" id="sidebarToggle"><i class="fas fa-bars"></i></button>
                <button class="navbar-toggle d-md-none" onclick="openMobileSidebar()"><i
                        class="fas fa-bars"></i></button>
                <span class="brand-text">
                    🎓 IE <span>Sor Annetta de Jesús</span> · Control de Aula
                </span>
            </div>
            <div class="navbar-right">
                @if(session('success'))
                <span
                    style="background:rgba(45,138,72,0.3);border:1px solid rgba(45,138,72,0.5);color:#a8f0bc;padding:4px 12px;border-radius:20px;font-size:0.78rem;font-weight:600">
                    ✓ {{ session('success') }}
                </span>
                @endif
                <div class="user-badge">
                    <div class="user-avatar">{{ $iniciales }}</div>
                    <span>{{ auth()->user()->name ?? 'Usuario' }}</span>
                </div>
            </div>
        </nav>

        <main class="page-content">
            @yield('content')
        </main>
    </div>

    <div id="modal-portal"></div>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebarMenu');
            const toggle  = document.getElementById('sidebarToggle');
            toggle?.addEventListener('click', () => {
                sidebar.classList.toggle('collapsed');
                document.body.classList.toggle('sidebar-collapsed');
            });
        });
        function openMobileSidebar() {
            document.getElementById('mobileSidebarMenu').style.display = 'block';
            document.getElementById('mobileSidebarOverlay').style.display = 'block';
        }
        function closeMobileSidebar() {
            document.getElementById('mobileSidebarMenu').style.display = 'none';
            document.getElementById('mobileSidebarOverlay').style.display = 'none';
        }
        function confirmLogout() {
            Swal.fire({
                title: '¿Cerrar sesión?',
                text: 'Tu sesión actual será finalizada.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, salir',
                cancelButtonText: 'Cancelar',
                reverseButtons: true,
                background: '#1a3d22',
                color: '#fff',
                confirmButtonColor: '#C0392B',
                cancelButtonColor: '#c8991a'
            }).then((result) => {
                if (result.isConfirmed) document.getElementById('logout-form').submit();
            });
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>
