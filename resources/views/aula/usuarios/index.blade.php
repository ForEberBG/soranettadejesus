@extends('layouts.app')
@section('title', 'Usuarios')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold" style="color:#1a5c2e">👥 Usuarios del Sistema</h4>
            <p class="text-muted mb-0" style="font-size:0.85rem">Gestión de accesos y roles</p>
        </div>
        <a href="{{ route('usuarios.aula.create') }}" class="btn btn-success">
            <i class="fas fa-plus me-1"></i> Nuevo Usuario
        </a>
    </div>

    @if(session('error'))
        <div class="alert alert-danger mb-3">{{ session('error') }}</div>
    @endif

    {{-- Tarjetas por rol --}}
    <div class="row g-3 mb-4">
        @php
            $roles = [
                'administrador' => ['color'=>'#1a5c2e','icon'=>'fa-user-shield','label'=>'Administradores'],
                'docente'       => ['color'=>'#2980b9','icon'=>'fa-chalkboard-teacher','label'=>'Docentes'],
                'tesorero'      => ['color'=>'#c8991a','icon'=>'fa-hand-holding-usd','label'=>'Tesoreros'],
                'padre'         => ['color'=>'#8e44ad','icon'=>'fa-users','label'=>'Padres de familia'],
            ];
        @endphp
        @foreach($roles as $rol => $info)
            <div class="col-6 col-md-3">
                <div class="card text-center" style="border-left:4px solid {{ $info['color'] }}">
                    <div class="card-body py-3">
                        <i class="fas {{ $info['icon'] }} fa-2x mb-2" style="color:{{ $info['color'] }};opacity:0.7"></i>
                        <div class="fw-bold" style="font-size:1.4rem;color:{{ $info['color'] }}">
                            {{ $usuarios->where('rol', $rol)->count() }}
                        </div>
                        <div class="text-muted" style="font-size:0.75rem">{{ $info['label'] }}</div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="card">
        <div class="card-header" style="background:#1a5c2e">
            <h6 class="mb-0" style="color:#f0c040">
                <i class="fas fa-users me-2"></i>Lista de usuarios ({{ $usuarios->count() }})
            </h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr style="background:#e8f5ec">
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Alumno vinculado</th>
                        <th>Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($usuarios as $u)
                    @php
                        $rolInfo = [
                            'administrador' => ['bg'=>'#e8f5ec','color'=>'#1a5c2e','label'=>'Administrador'],
                            'docente'       => ['bg'=>'#eaf3fb','color'=>'#1a5276','label'=>'Docente'],
                            'tesorero'      => ['bg'=>'#fdf6e0','color'=>'#7a5a00','label'=>'Tesorero'],
                            'padre'         => ['bg'=>'#f5eefa','color'=>'#6c3483','label'=>'Padre'],
                        ][$u->rol] ?? ['bg'=>'#f0f0f0','color'=>'#555','label'=>$u->rol];
                    @endphp
                    <tr>
                        <td class="text-muted">{{ $loop->iteration }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div style="width:34px;height:34px;border-radius:50%;background:{{ $rolInfo['bg'] }};border:2px solid {{ $rolInfo['color'] }};display:flex;align-items:center;justify-content:center;font-size:0.8rem;font-weight:700;color:{{ $rolInfo['color'] }}">
                                    {{ strtoupper(substr($u->name,0,1)) }}
                                </div>
                                <span class="fw-bold">{{ $u->name }}</span>
                                @if($u->id === auth()->id())
                                    <span class="badge" style="background:#e8f5ec;color:#1a5c2e;font-size:0.65rem">Tú</span>
                                @endif
                            </div>
                        </td>
                        <td class="text-muted">{{ $u->email }}</td>
                        <td>
                            <span class="badge" style="background:{{ $rolInfo['bg'] }};color:{{ $rolInfo['color'] }};border:1px solid {{ $rolInfo['color'] }}">
                                {{ $rolInfo['label'] }}
                            </span>
                        </td>
                        <td class="text-muted" style="font-size:0.85rem">
                            {{ $u->alumno->nombre_completo ?? '—' }}
                        </td>
                        <td>
                            <span class="badge {{ $u->activo ? 'bg-success' : 'bg-secondary' }}">
                                {{ $u->activo ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('usuarios.aula.edit', $u) }}"
                               class="btn btn-sm btn-warning me-1">
                                <i class="fas fa-edit"></i>
                            </a>
                            @if($u->id !== auth()->id())
                            <form method="POST" action="{{ route('usuarios.aula.destroy', $u) }}"
                                  style="display:inline"
                                  onsubmit="return confirm('¿Eliminar a {{ $u->name }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">Sin usuarios registrados</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
