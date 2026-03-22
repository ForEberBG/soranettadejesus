@extends('layouts.app')
@section('title', 'Gastos')

@section('content')
<div class="container-fluid">

    <div class="mb-4">
        <h4 class="fw-bold" style="color:#1a5c2e">🧾 Registro de Gastos</h4>
        <p class="text-muted mb-0" style="font-size:0.85rem">Control de egresos del aula</p>
    </div>

    <div class="row g-4">

        {{-- Formulario --}}
        <div class="col-md-5">
            <div class="card">
                <div class="card-header" style="background:#1a5c2e">
                    <h6 class="mb-0" style="color:#f0c040"><i class="fas fa-plus me-2"></i>Registrar gasto</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('gastos.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted" style="font-size:0.78rem;text-transform:uppercase">Descripción *</label>
                            <input type="text" name="descripcion" class="form-control"
                                   value="{{ old('descripcion') }}"
                                   placeholder="Ej: Materiales para decoración" required>
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label fw-bold text-muted" style="font-size:0.78rem;text-transform:uppercase">Monto (S/) *</label>
                                <input type="number" name="monto" class="form-control"
                                       step="0.01" min="0" value="{{ old('monto') }}"
                                       placeholder="0.00" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-bold text-muted" style="font-size:0.78rem;text-transform:uppercase">Fecha *</label>
                                <input type="date" name="fecha" class="form-control"
                                       value="{{ old('fecha', date('Y-m-d')) }}" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted" style="font-size:0.78rem;text-transform:uppercase">Categoría *</label>
                            <select name="categoria" class="form-select" required>
                                @foreach(['Material','Alimentación','Transporte','Decoración','Limpieza','Impresiones','Otro'] as $cat)
                                    <option {{ old('categoria') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted" style="font-size:0.78rem;text-transform:uppercase">Actividad relacionada</label>
                            <select name="actividad_id" class="form-select">
                                <option value="">— General —</option>
                                @foreach($actividades as $act)
                                    <option value="{{ $act->id }}" {{ old('actividad_id') == $act->id ? 'selected' : '' }}>
                                        {{ $act->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted" style="font-size:0.78rem;text-transform:uppercase">Comprobante</label>
                            <input type="text" name="comprobante" class="form-control"
                                   placeholder="Nro. boleta, factura..." value="{{ old('comprobante') }}">
                        </div>
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-save me-1"></i> Registrar gasto
                        </button>
                    </form>
                </div>
            </div>

            {{-- Gastos por categoría --}}
            <div class="card mt-4">
                <div class="card-header" style="background:#1a5c2e">
                    <h6 class="mb-0" style="color:#f0c040"><i class="fas fa-chart-pie me-2"></i>Por categoría</h6>
                </div>
                <div class="card-body">
                    @forelse($porCategoria as $cat => $monto)
                        @php $pct = $totalGastos > 0 ? round($monto / $totalGastos * 100) : 0; @endphp
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1" style="font-size:0.85rem">
                                <span class="fw-bold">{{ $cat }}</span>
                                <span class="text-muted">S/ {{ number_format($monto,2) }} ({{ $pct }}%)</span>
                            </div>
                            <div class="progress" style="height:6px">
                                <div class="progress-bar" style="width:{{ $pct }}%;background:#c8991a"></div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-3">Sin gastos registrados</div>
                    @endforelse
                    @if($totalGastos > 0)
                        <div class="border-top pt-2 mt-2 d-flex justify-content-between fw-bold">
                            <span>Total</span>
                            <span class="text-danger">S/ {{ number_format($totalGastos,2) }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Historial --}}
        <div class="col-md-7">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center" style="background:#1a5c2e">
                    <h6 class="mb-0" style="color:#f0c040"><i class="fas fa-list me-2"></i>Historial de gastos</h6>
                    <form method="GET" style="margin:0">
                        <select name="categoria" class="form-select form-select-sm" style="width:150px;background:#2d8a48;color:#fff;border-color:#c8991a" onchange="this.form.submit()">
                            <option value="">Todas</option>
                            @foreach(['Material','Alimentación','Transporte','Decoración','Limpieza','Impresiones','Otro'] as $cat)
                                <option {{ request('categoria') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </form>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0" style="font-size:0.85rem">
                        <thead>
                            <tr style="background:#e8f5ec">
                                <th>Fecha</th>
                                <th>Descripción</th>
                                <th>Categoría</th>
                                <th>Actividad</th>
                                <th class="text-end">Monto</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($gastos as $gasto)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($gasto->fecha)->format('d/m/Y') }}</td>
                                <td class="fw-bold">{{ $gasto->descripcion }}</td>
                                <td><span class="badge" style="background:#fdf6e0;color:#7a5a00;border:1px solid #c8991a">{{ $gasto->categoria }}</span></td>
                                <td class="text-muted">{{ $gasto->actividad->nombre ?? '—' }}</td>
                                <td class="text-end fw-bold text-danger">S/ {{ number_format($gasto->monto,2) }}</td>
                                <td>
                                    <form method="POST" action="{{ route('gastos.destroy', $gasto) }}"
                                          onsubmit="return confirm('¿Eliminar este gasto?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Sin gastos registrados</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($gastos->hasPages())
                <div class="card-footer">{{ $gastos->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
