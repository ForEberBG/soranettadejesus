@extends('layouts.app')
@section('title', 'Alumnos - IE Sor Annetta')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold" style="color:#1a5c2e">🎒 Alumnos del Aula</h4>
            <p class="text-muted mb-0" style="font-size:0.85rem">Gestión de alumnos y apoderados</p>
        </div>
        <div style="display:flex; gap:8px">
            <a href="{{ route('pdf.alumnos') }}" class="btn btn-danger" target="_blank">
                <i class="fas fa-file-pdf me-1"></i> PDF
            </a>
            <a href="{{ route('alumnos.create') }}" class="btn btn-success">
                <i class="fas fa-plus me-1"></i> Nuevo Alumno
            </a>
        </div>
    </div>

    {{-- Buscador --}}
    <div class="card mb-4">
        <div class="card-body py-2">
            <form method="GET" class="d-flex gap-2">
                <input type="text" name="buscar" value="{{ request('buscar') }}" class="form-control"
                    placeholder="Buscar por nombre o apellido...">
                <button type="submit" class="btn btn-outline-success">
                    <i class="fas fa-search"></i>
                </button>
                @if(request('buscar'))
                <a href="{{ route('alumnos.index') }}" class="btn btn-outline-secondary">Limpiar</a>
                @endif
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr style="background:#1a5c2e">
                            <th style="color:#f0c040">#</th>
                            <th style="color:#f0c040">Alumno</th>
                            <th style="color:#f0c040">Apoderado</th>
                            <th style="color:#f0c040">Celular</th>
                            <th style="color:#f0c040">Estado</th>
                            <th style="color:#f0c040">Deuda</th>
                            <th style="color:#f0c040" class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($alumnos as $al)
                        <tr>
                            <td class="text-muted">{{ $loop->iteration }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    {{-- Foto --}}
                                    @if($al->foto)
                                    <img src="{{ asset($al->foto) }}" alt="Foto"
                                        style="width:42px;height:42px;border-radius:50%;object-fit:cover;border:2px solid #1a5c2e;flex-shrink:0">
                                    @else
                                    <div
                                        style="width:42px;height:42px;border-radius:50%;background:#e8f5ec;border:2px solid #1a5c2e;display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0">
                                        🎒
                                    </div>
                                    @endif
                                    <div>
                                        <div class="fw-bold">{{ $al->nombre_completo }}</div>
                                        <div class="text-muted" style="font-size:0.78rem">DNI: {{ $al->dni ?? '—' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $al->apoderado }}</td>
                            <td>{{ $al->celular ?? '—' }}</td>
                            <td>
                                @php
                                $deuda = $al->deuda_total;
                                $pagado = $al->total_pagado;
                                @endphp
                                @if($deuda <= 0) <span class="badge bg-success">Al día ✓</span>
                                    @elseif($pagado > 0)
                                    <span class="badge bg-warning text-dark">Pago parcial</span>
                                    @else
                                    <span class="badge bg-danger">Sin pagar</span>
                                    @endif
                            </td>
                            <td>
                                @if($al->deuda_total <= 0) <span class="text-success fw-bold">✓ Completo</span>
                                    @else
                                    <div>
                                        <div class="text-danger fw-bold" style="font-size:0.85rem">
                                            Falta: S/ {{ number_format($al->deuda_total,2) }}
                                        </div>
                                        @if($al->total_pagado > 0)
                                        <div class="text-muted" style="font-size:0.75rem">
                                            Pagado: S/ {{ number_format($al->total_pagado,2) }}
                                        </div>
                                        @endif
                                    </div>
                                    @endif
                            </td>

                            <td class="text-center">
                                <a href="{{ route('alumnos.edit', $al) }}" class="btn btn-sm btn-warning me-1">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('alumnos.destroy', $al) }}" style="display:inline"
                                    onsubmit="return confirm('¿Quitar a {{ $al->nombre_completo }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fas fa-user-graduate fa-2x mb-2 d-block opacity-25"></i>
                                No hay alumnos registrados.
                                <a href="{{ route('alumnos.create') }}" style="color:#1a5c2e">Registrar el primero</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($alumnos->hasPages())
        <div class="card-footer">
            {{ $alumnos->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
