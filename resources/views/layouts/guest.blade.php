<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'IE Sor Annetta de Jesús - Control de Aula')</title>
    <link href="https://fonts.googleapis.com/css2?family=Merriweather+Sans:wght@700;800&family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('volt/assets/css/volt.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --verde-oscuro:  #1a5c2e;
            --verde-medio:   #2d8a48;
            --dorado:        #c8991a;
            --dorado-claro:  #f0c040;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            min-height: 100vh;
            font-family: 'Nunito', sans-serif;
            background: var(--verde-oscuro);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            position: relative;
        }

        /* Fondo verde con gradiente */
        body::before {
            content: "";
            position: fixed;
            inset: 0;
            background:
                radial-gradient(ellipse at 30% 40%, rgba(45,138,72,0.15) 0%, transparent 50%),
                radial-gradient(ellipse at 80% 80%, rgba(200,153,26,0.08) 0%, transparent 40%),
                linear-gradient(160deg, #0d2e15 0%, #122a18 40%, #1a3d22 70%, #143320 100%);
            z-index: 0;
        }

        /* Overlay con tono dorado sutil */
        body::after {
            content: "";
            position: fixed;
            inset: 0;
            background: linear-gradient(135deg,
                rgba(26,92,46,0.85) 0%,
                rgba(200,153,26,0.1) 100%);
            z-index: 0;
        }

        main {
            position: relative;
            z-index: 1;
            width: 100%;
        }

        footer {
            position: relative;
            z-index: 1;
            color: rgba(255,255,255,0.35);
            font-size: 0.72rem;
            padding: 12px;
            text-align: center;
        }
    </style>
</head>

<body>
    <main class="w-100" style="max-width:920px; padding:20px;">
        @yield('content')
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
