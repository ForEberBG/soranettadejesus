@extends('layouts.app')
@section('title', 'Caja Chica')

@section('content')
<div class="container-fluid">

    <div class="mb-4">
        <h4 class="fw-bold" style="color:#1a5c2e">💵 Caja Chica</h4>
        <p class="text-muted mb-0" style="font-size:0.85rem">Control de fondos y egresos del aula</p>
    </div>

    @if(session('error'))
        <div class="alert alert-danger mb-3" style="border-radius:10px;border-left:4px solid #c0392b">
            ⚠️ {{ session('error') }}
        </div>
    @endif

    {{-- CAJA ACTIVA --}}
    @if($cajaActiva)
    <div class="row g-4 mb-4">

        {{-- Estado de la caja --}}
        <div class="col-md-4">
            <div class="card h-100" style="border:2px solid #1a5c2e">
                <div class="card-header" style="background:#1a5c2e">
                    <h6 class="mb-0" style="color:#f0c040">
                        <i class="fas fa-cash-register me-2"></i>Caja activa
                    </h6>
                </div>
                <div class="card-body text-center py-4">
                    <div style="font-size:0.75rem;color:#888;text-transform:uppercase;margin-bottom:4px">Saldo disponible</div>
                    <div style="font-size:2.5rem;font-weight:800;color:{{ $cajaActiva->saldo_actual > 0 ? '#1a5c2e' : '#c0392b' }}">
                        S/ {{ number_format($cajaActiva->saldo_actual,2) }}
                    </div>
                    <div style="font-size:0.78rem;color:#888;margin-top:6px">
                        Apertura: {{ $cajaActiva->fecha_apertura->format('d/m/Y H:i') }}
                    </div>
                    @if($cajaActiva->descripcion)
                        <div style="font-size:0.78rem;color:#555;margin-top:4px">{{ $cajaActiva->descripcion }}</div>
                    @endif
                    <hr>
                    <div class="row g-2 text-center">
                        <div class="col-6">
                            <div style="font-size:0.7rem;color:#888">Monto inicial</div>
                            <div style="font-weight:700;color:#c8991a">S/ {{ number_format($cajaActiva->monto_inicial,2) }}</div>
                        </div>
                        <div class="col-6">
                            <div style="font-size:0.7rem;color:#888">Total egresos</div>
                            <div style="font-weight:700;color:#c0392b">S/ {{ number_format($cajaActiva->total_egresos,2) }}</div>
                        </div>
                    </div>
                    <hr>
                    {{-- Barra de saldo --}}
                    @php $pct = $cajaActiva->monto_inicial > 0 ? round($cajaActiva->saldo_actual / $cajaActiva->monto_inicial * 100) : 0; @endphp
                    <div style="font-size:0.72rem;color:#888;margin-bottom:4px">{{ $pct }}% disponible</div>
                    <div class="progress" style="height:8px">
                        <div class="progress-bar {{ $pct > 50 ? 'bg-success' : ($pct > 20 ? 'bg-warning' : 'bg-danger') }}"
                             style="width:{{ $pct }}%"></div>
                    </div>
                    <hr>
                    <form method="POST" action="{{ route('caja.cerrar') }}"
                          onsubmit="return confirm('¿Cerrar la caja actual?')">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                            <i class="fas fa-lock me-1"></i> Cerrar caja
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            {{-- Registrar egreso --}}
            <div class="card mb-3">
                <div class="card-header" style="background:#1a5c2e">
                    <h6 class="mb-0" style="color:#f0c040">
                        <i class="fas fa-minus-circle me-2"></i>Registrar egreso
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('caja.egreso') }}">
                        @csrf
                        <div class="row g-2">
                            <div class="col-md-5">
                                <label class="form-label fw-bold text-muted" style="font-size:0.75rem;text-transform:uppercase">Descripción *</label>
                                <input type="text" name="descripcion" class="form-control form-control-sm"
                                       placeholder="Ej: Compra de útiles" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold text-muted" style="font-size:0.75rem;text-transform:uppercase">Categoría *</label>
                                <select name="categoria" class="form-select form-select-sm" required>
                                    @foreach(['Material','Alimentación','Transporte','Decoración','Limpieza','Impresiones','Otro'] as $cat)
                                        <option>{{ $cat }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-bold text-muted" style="font-size:0.75rem;text-transform:uppercase">Monto *</label>
                                <input type="number" name="monto" class="form-control form-control-sm"
                                       step="0.01" min="0.01" placeholder="0.00" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-bold text-muted" style="font-size:0.75rem;text-transform:uppercase">Fecha *</label>
                                <input type="date" name="fecha" class="form-control form-control-sm"
                                       value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold text-muted" style="font-size:0.75rem;text-transform:uppercase">Comprobante</label>
                                <input type="text" name="comprobante" class="form-control form-control-sm"
                                       placeholder="Nro. boleta...">
                            </div>
                            <div class="col-md-8 d-flex align-items-end">
                                <button type="submit" class="btn btn-danger btn-sm w-100">
                                    <i class="fas fa-minus me-1"></i> Registrar egreso
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Reponer fondos --}}
            <div class="card">
                <div class="card-header" style="background:#c8991a">
                    <h6 class="mb-0" style="color:#fff">
                        <i class="fas fa-plus-circle me-2"></i>Reponer fondos
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('caja.reponer') }}">
                        @csrf
                        <div class="row g-2">
                            <div class="col-md-4">
                                <label class="form-label fw-bold text-muted" style="font-size:0.75rem;text-transform:uppercase">Monto a reponer *</label>
                                <input type="number" name="monto" class="form-control form-control-sm"
                                       step="0.01" min="0.01" placeholder="0.00" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted" style="font-size:0.75rem;text-transform:uppercase">Descripción</label>
                                <input type="text" name="descripcion" class="form-control form-control-sm"
                                       placeholder="Ej: Reposición aprobada en reunión">
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-sm w-100"
                                        style="background:#c8991a;color:#fff">
                                    <i class="fas fa-plus"></i> Reponer
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Movimientos de la caja activa --}}
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center" style="background:#1a5c2e">
            <h6 class="mb-0" style="color:#f0c040">
                <i class="fas fa-list me-2"></i>Movimientos de caja activa
            </h6>
            <a href="{{ route('caja.pdf', $cajaActiva) }}" target="_blank"
               class="btn btn-sm btn-danger">
                <i class="fas fa-file-pdf me-1"></i> PDF
            </a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0" style="font-size:0.85rem">
                <thead>
                    <tr style="background:#e8f5ec">
                        <th>Fecha</th>
                        <th>Descripción</th>
                        <th>Categoría</th>
                        <th>Comprobante</th>
                        <th>Tipo</th>
                        <th class="text-end">Monto</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cajaActiva->movimientos->sortByDesc('fecha') as $mov)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($mov->fecha)->format('d/m/Y') }}</td>
                        <td class="fw-bold">{{ $mov->descripcion }}</td>
                        <td><span class="badge" style="background:#fdf6e0;color:#7a5a00;border:1px solid #c8991a">{{ $mov->categoria }}</span></td>
                        <td class="text-muted">{{ $mov->comprobante ?? '—' }}</td>
                        <td>
                            @if($mov->tipo === 'egreso')
                                <span class="badge bg-danger">Egreso</span>
                            @else
                                <span class="badge" style="background:#c8991a;color:#fff">Reposición</span>
                            @endif
                        </td>
                        <td class="text-end fw-bold {{ $mov->tipo === 'egreso' ? 'text-danger' : 'text-success' }}">
                            {{ $mov->tipo === 'egreso' ? '–' : '+' }}S/ {{ number_format($mov->monto,2) }}
                        </td>
                        <td>
                            <form method="POST" action="{{ route('caja.movimiento.eliminar', $mov) }}"
                                  onsubmit="return confirm('¿Eliminar este movimiento?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-3 text-muted">Sin movimientos aún</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @else
    {{-- No hay caja abierta --}}
    <div class="card mb-4">
        <div class="card-header" style="background:#1a5c2e">
            <h6 class="mb-0" style="color:#f0c040">
                <i class="fas fa-plus-circle me-2"></i>Abrir nueva caja
            </h6>
        </div>
        <div class="card-body" style="max-width:500px">
            <div class="alert alert-warning mb-3" style="border-radius:8px">
                No hay caja abierta actualmente. Abre una nueva para registrar movimientos.
            </div>
            <form method="POST" action="{{ route('caja.abrir') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold text-muted" style="font-size:0.78rem;text-transform:uppercase">
                        Monto inicial (S/) *
                    </label>
                    <input type="number" name="monto_inicial" class="form-control"
                           step="0.01" min="1" placeholder="Ej: 200.00" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold text-muted" style="font-size:0.78rem;text-transform:uppercase">
                        Descripción
                    </label>
                    <input type="text" name="descripcion" class="form-control"
                           placeholder="Ej: Caja marzo 2026">
                </div>
                <button type="submit" class="btn btn-success w-100">
                    <i class="fas fa-unlock me-1"></i> Abrir caja
                </button>
            </form>
        </div>
    </div>
    @endif

    {{-- Historial de cajas --}}
    <div class="card">
        <div class="card-header" style="background:#1a5c2e">
            <h6 class="mb-0" style="color:#f0c040">
                <i class="fas fa-history me-2"></i>Historial de cajas
            </h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0" style="font-size:0.85rem">
                <thead>
                    <tr style="background:#e8f5ec">
                        <th>Apertura</th>
                        <th>Cierre</th>
                        <th>Descripción</th>
                        <th class="text-end">Monto inicial</th>
                        <th class="text-end">Egresos</th>
                        <th class="text-end">Reposiciones</th>
                        <th class="text-end">Saldo final</th>
                        <th>Estado</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($historial as $caja)
                    <tr>
                        <td>{{ $caja->fecha_apertura ? $caja->fecha_apertura->format('d/m/Y H:i') : '—' }}</td>
                        <td>{{ $caja->fecha_cierre ? $caja->fecha_cierre->format('d/m/Y H:i') : '—' }}</td>
                        <td>{{ $caja->descripcion ?? '—' }}</td>
                        <td class="text-end">S/ {{ number_format($caja->monto_inicial,2) }}</td>
                        <td class="text-end text-danger">S/ {{ number_format($caja->total_egresos,2) }}</td>
                        <td class="text-end text-success">S/ {{ number_format($caja->total_reposiciones,2) }}</td>
                        <td class="text-end fw-bold">S/ {{ number_format($caja->saldo_actual,2) }}</td>
                        <td>
                            <span class="badge {{ $caja->estado === 'abierta' ? 'bg-success' : 'bg-secondary' }}">
                                {{ ucfirst($caja->estado) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('caja.pdf', $caja) }}" target="_blank"
                               class="btn btn-sm btn-outline-danger">
                                <i class="fas fa-file-pdf"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="text-center py-3 text-muted">Sin historial</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($historial->hasPages())
        <div class="card-footer">{{ $historial->links() }}</div>
        @endif
    </div>

</div>
@endsection
