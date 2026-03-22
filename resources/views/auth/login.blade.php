@extends('layouts.guest')
@section('title', 'Iniciar Sesión - IE Sor Annetta de Jesús')
@section('content')

<div class="row g-0 shadow-lg rounded-4 overflow-hidden" style="min-height:480px">

    {{-- Panel izquierdo decorativo --}}
    <div class="col-md-5 d-none d-md-flex flex-column justify-content-between p-4"
         style="background: linear-gradient(160deg, #1a5c2e 0%, #145224 60%, #2d8a48 100%);">

        {{-- Logo --}}
        <div class="text-center mt-2">
            @php $config = App\Models\Configuracion::first(); @endphp
            @if($config && $config->logo)
                <img src="{{ asset($config->logo) }}" alt="Logo"
                     style="width:120px;height:120px;border-radius:12px;border:3px solid #c8991a;object-fit:contain;box-shadow:0 0 30px rgba(200,153,26,0.4);background:rgba(255,255,255,0.1);padding:6px">
            @else
                <div style="width:120px;height:120px;border-radius:12px;background:rgba(200,153,26,0.2);border:3px solid #c8991a;display:flex;align-items:center;justify-content:center;margin:0 auto">
                    <i class="fas fa-graduation-cap" style="font-size:3rem;color:#f0c040"></i>
                </div>
            @endif
        </div>

        {{-- Texto central --}}
        <div class="text-center">
            <h2 style="font-family:'Merriweather Sans',sans-serif;color:#f0c040;font-size:1.4rem;font-weight:800;line-height:1.2">
                {{ $config->nombre ?? 'IE Sor Annetta de Jesús' }}
            </h2>
            <p style="color:rgba(255,255,255,0.6);font-size:0.78rem;margin-top:8px;letter-spacing:2px;text-transform:uppercase">
                Pucallpa — Ucayali
            </p>
            <div style="width:50px;height:2px;background:#c8991a;margin:14px auto"></div>
            <p style="color:rgba(255,255,255,0.5);font-size:0.8rem">
                Sistema de Control de Aula
            </p>
            @if($config && $config->aula)
            <div style="background:rgba(200,153,26,0.15);border:1px solid rgba(200,153,26,0.3);border-radius:8px;padding:8px 14px;margin-top:12px;display:inline-block">
                <div style="font-size:0.7rem;color:rgba(255,255,255,0.4);text-transform:uppercase;letter-spacing:0.8px">Aula</div>
                <div style="font-size:0.95rem;font-weight:700;color:#f0c040">
                    {{ $config->aula }} · {{ $config->anio_escolar ?? date('Y') }}
                </div>
                @if($config->docente)
                <div style="font-size:0.72rem;color:rgba(255,255,255,0.5);margin-top:2px">
                    {{ $config->docente }}
                </div>
                @endif
            </div>
            @endif
        </div>

        {{-- Footer decorativo --}}
        <div class="text-center">
            <small style="color:rgba(255,255,255,0.3)">
                <i class="fas fa-map-marker-alt me-1" style="color:#c8991a"></i>
                {{ $config->direccion ?? 'Pucallpa, Perú' }}
            </small>
        </div>
    </div>

    {{-- Panel derecho - Formulario --}}
    <div class="col-md-7 d-flex align-items-center"
         style="background:rgba(255,255,255,0.97)">
        <div class="w-100 p-4 p-md-5">

            {{-- Header móvil --}}
            <div class="d-md-none text-center mb-4">
                @if($config && $config->logo)
                    <img src="{{ asset($config->logo) }}" alt="Logo"
                         style="width:70px;height:70px;border-radius:8px;border:2px solid #c8991a;object-fit:contain;padding:3px">
                @endif
                <h4 style="font-family:'Merriweather Sans',sans-serif;color:#1a5c2e;margin-top:8px">
                    {{ $config->nombre ?? 'IE Sor Annetta de Jesús' }}
                </h4>
            </div>

            {{-- Ícono escolar --}}
            <div style="width:48px;height:48px;border-radius:12px;background:#e8f5ec;border:2px solid #2d8a48;display:flex;align-items:center;justify-content:center;margin-bottom:16px">
                <i class="fas fa-graduation-cap" style="color:#1a5c2e;font-size:1.3rem"></i>
            </div>

            <h3 style="font-family:'Merriweather Sans',sans-serif;color:#1a5c2e;font-weight:800;margin-bottom:4px">
                Bienvenido 👋
            </h3>
            <p style="color:#888;font-size:0.875rem;margin-bottom:28px">
                Ingresa tus credenciales para continuar
            </p>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label style="font-weight:700;color:#1a5c2e;font-size:0.875rem">
                        <i class="fas fa-envelope me-1" style="color:#2d8a48"></i>
                        Correo electrónico
                    </label>
                    <input type="email" name="email"
                           class="form-control mt-1 @error('email') is-invalid @enderror"
                           style="border:2px solid #e0e0e0;border-radius:8px;padding:10px 14px;font-size:0.9rem;transition:border-color 0.2s"
                           onfocus="this.style.borderColor='#2d8a48'"
                           onblur="this.style.borderColor='#e0e0e0'"
                           value="{{ old('email') }}" required autofocus>
                    @error('email')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label style="font-weight:700;color:#1a5c2e;font-size:0.875rem">
                        <i class="fas fa-lock me-1" style="color:#2d8a48"></i>
                        Contraseña
                    </label>
                    <div style="position:relative">
                        <input type="password" name="password" id="passwordInput"
                               class="form-control mt-1 @error('password') is-invalid @enderror"
                               style="border:2px solid #e0e0e0;border-radius:8px;padding:10px 40px 10px 14px;font-size:0.9rem;transition:border-color 0.2s"
                               onfocus="this.style.borderColor='#2d8a48'"
                               onblur="this.style.borderColor='#e0e0e0'"
                               required>
                        <button type="button" onclick="togglePass()"
                                style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;color:#aaa;cursor:pointer;padding:0;margin-top:2px">
                            <i class="fas fa-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="remember" id="remember"
                               style="border-color:#2d8a48;accent-color:#1a5c2e">
                        <label class="form-check-label" for="remember"
                               style="font-size:0.875rem;color:#666">Recordarme</label>
                    </div>
                </div>

                <button type="submit" class="w-100 py-2 fw-bold"
                        style="background:linear-gradient(135deg,#1a5c2e,#2d8a48);border:none;border-radius:8px;color:white;font-size:1rem;font-family:'Merriweather Sans',sans-serif;letter-spacing:0.5px;transition:all 0.2s;cursor:pointer;border-bottom:3px solid #c8991a"
                        onmouseover="this.style.opacity='0.9'"
                        onmouseout="this.style.opacity='1'">
                    <i class="fas fa-sign-in-alt me-2"></i> Ingresar al Sistema
                </button>

                {{-- Roles disponibles --}}
                <div class="mt-4 p-3" style="background:#f5f7f5;border-radius:8px;border:1px solid #dde8df">
                    <div style="font-size:0.72rem;font-weight:700;color:#1a5c2e;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:8px">
                        Accesos disponibles
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <span style="background:#e8f5ec;color:#1a5c2e;padding:3px 10px;border-radius:20px;font-size:0.72rem;border:1px solid #2d8a48">👨‍💼 Administrador</span>
                        <span style="background:#eaf3fb;color:#1a5276;padding:3px 10px;border-radius:20px;font-size:0.72rem;border:1px solid #2980b9">👩‍🏫 Docente</span>
                        <span style="background:#fdf6e0;color:#7a5a00;padding:3px 10px;border-radius:20px;font-size:0.72rem;border:1px solid #c8991a">💰 Tesorero</span>
                        <span style="background:#f5eefa;color:#6c3483;padding:3px 10px;border-radius:20px;font-size:0.72rem;border:1px solid #8e44ad">👨‍👩‍👧 Padre</span>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>

{{-- Footer --}}
<div class="text-center mt-3">
    <small style="color:rgba(255,255,255,0.4);font-size:0.72rem">
        © {{ date('Y') }} IE Sor Annetta de Jesús · Pucallpa · Sistema de Control de Aula
    </small>
</div>

<script>
function togglePass() {
    const input = document.getElementById('passwordInput');
    const icon  = document.getElementById('eyeIcon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}
</script>
@endsection
