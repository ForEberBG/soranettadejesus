@extends('layouts.app')
@section('title', 'Cuotas y Cobros')

@section('content')
<div class="container-fluid">

    <div class="mb-4">
        <h4 class="fw-bold" style="color:#1a5c2e">💰 Cuotas y Cobros</h4>
        <p class="text-muted mb-0" style="font-size:0.85rem">Registro de pagos por actividad</p>
    </div>

    <div class="row g-4">

        {{-- Actividades con avance --}}
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center" style="background:#1a5c2e">
                    <h6 class="mb-0" style="color:#f0c040"><i class="fas fa-calendar-alt me-2"></i>Actividades</h6>
                    <a href="{{ route('actividades.create') }}" class="btn btn-sm" style="background:#c8991a;color:#fff">+ Nueva</a>
                </div>
                <div class="card-body">
                    @forelse($actividades as $act)
                        @php
                            $meta    = $act->cuota * $totalAlumnos;
                            $cobrado = $act->cobros_sum_monto ?? 0;
                            $pct     = $meta > 0 ? min(100, round($cobrado / $meta * 100)) : 0;
                            $color   = $pct >= 100 ? 'success' : ($pct >= 50 ? 'warning' : 'danger');
                        @endphp
                        <div class="mb-3 pb-3 border-bottom">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <div>
                                    <div class="fw-bold" style="font-size:0.9rem">{{ $act->nombre }}</div>
                                    <div class="text-muted" style="font-size:0.75rem">
                                        Cuota: S/ {{ number_format($act->cuota,2) }} ·
                                        Vence: {{ $act->fecha_limite ? \Carbon\Carbon::parse($act->fecha_limite)->format('d/m/Y') : '—' }}
                                    </div>
                                </div>
                                <span class="badge bg-{{ $color }}">{{ $pct }}%</span>
                            </div>
                            <div class="progress" style="height:6px">
                                <div class="progress-bar bg-{{ $color }}" style="width:{{ $pct }}%"></div>
                            </div>
                            <div class="text-muted mt-1" style="font-size:0.72rem">
                                S/ {{ number_format($cobrado,2) }} / S/ {{ number_format($meta,2) }}
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-calendar fa-2x mb-2 d-block opacity-25"></i>
                            Sin actividades.
                            <a href="{{ route('actividades.create') }}" style="color:#1a5c2e">Crear una</a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Formulario de cobro --}}
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header" style="background:#1a5c2e">
                    <h6 class="mb-0" style="color:#f0c040"><i class="fas fa-hand-holding-usd me-2"></i>Registrar cobro</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('cuotas.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted" style="font-size:0.78rem;text-transform:uppercase">Alumno *</label>
                                <select name="alumno_id" class="form-select" required>
                                    <option value="">— Seleccionar —</option>
                                    @foreach($alumnos as $al)
                                        <option value="{{ $al->id }}" {{ old('alumno_id') == $al->id ? 'selected' : '' }}>
                                            {{ $al->nombre_completo }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted" style="font-size:0.78rem;text-transform:uppercase">Actividad *</label>
                                <select name="actividad_id" id="sel-actividad" class="form-select" required>
                                    <option value="">— Seleccionar —</option>
                                    @foreach($actividades as $act)
                                        <option value="{{ $act->id }}" data-cuota="{{ $act->cuota }}"
                                                {{ old('actividad_id') == $act->id ? 'selected' : '' }}>
                                            {{ $act->nombre }} (S/ {{ number_format($act->cuota,2) }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold text-muted" style="font-size:0.78rem;text-transform:uppercase">Monto (S/) *</label>
                                <input type="number" name="monto" id="inp-monto" class="form-control"
                                       step="0.01" min="0" value="{{ old('monto') }}" placeholder="0.00" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold text-muted" style="font-size:0.78rem;text-transform:uppercase">Fecha *</label>
                                <input type="date" name="fecha" class="form-control"
                                       value="{{ old('fecha', date('Y-m-d')) }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold text-muted" style="font-size:0.78rem;text-transform:uppercase">Método de pago *</label>
                                <select name="metodo_pago" id="sel-metodo" class="form-select" required onchange="toggleCaptura()">
                                    <option value="efectivo" {{ old('metodo_pago') === 'efectivo' ? 'selected' : '' }}>💵 Efectivo</option>
                                    <option value="yape"     {{ old('metodo_pago') === 'yape'     ? 'selected' : '' }}>📱 Yape</option>
                                    <option value="plin"     {{ old('metodo_pago') === 'plin'     ? 'selected' : '' }}>📱 Plin</option>
                                    <option value="otro"     {{ old('metodo_pago') === 'otro'     ? 'selected' : '' }}>🔄 Otro</option>
                                </select>
                            </div>

                            {{-- Captura de pago (Yape/Plin) --}}
                            <div class="col-12" id="div-captura" style="display:none">
                                <label class="form-label fw-bold text-muted" style="font-size:0.78rem;text-transform:uppercase">
                                    📸 Captura del pago
                                </label>
                                <input type="file" name="captura" class="form-control" accept="image/*"
                                       id="inp-captura" onchange="previewCaptura(this)">
                                <div id="captura-preview" class="mt-2"></div>
                                <div class="text-muted mt-1" style="font-size:0.72rem">
                                    Sube la captura de pantalla del Yape o Plin
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold text-muted" style="font-size:0.78rem;text-transform:uppercase">Observaciones</label>
                                <input type="text" name="observaciones" class="form-control"
                                       placeholder="Ej: Pago parcial, abono..." value="{{ old('observaciones') }}">
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="fas fa-save me-1"></i> Registrar cobro
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Historial --}}
            <div class="card">
                <div class="card-header" style="background:#1a5c2e">
                    <h6 class="mb-0" style="color:#f0c040"><i class="fas fa-list me-2"></i>Historial de cobros</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0" style="font-size:0.85rem">
                        <thead>
                            <tr style="background:#e8f5ec">
                                <th>Fecha</th>
                                <th>Alumno</th>
                                <th>Actividad</th>
                                <th>Método</th>
                                <th>Captura</th>
                                <th class="text-end">Monto</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($cobros as $cobro)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($cobro->fecha)->format('d/m/Y') }}</td>
                                <td class="fw-bold">{{ $cobro->alumno->nombre_completo }}</td>
                                <td>{{ $cobro->actividad->nombre }}</td>
                                <td>
                                    @php
                                        $iconos = ['efectivo'=>'💵','yape'=>'📱','plin'=>'📱','otro'=>'🔄'];
                                        $labels = ['efectivo'=>'Efectivo','yape'=>'Yape','plin'=>'Plin','otro'=>'Otro'];
                                    @endphp
                                    <span class="badge" style="background:#e8f5ec;color:#1a5c2e;border:1px solid #2d8a48">
                                        {{ $iconos[$cobro->metodo_pago ?? 'efectivo'] ?? '💵' }}
                                        {{ $labels[$cobro->metodo_pago ?? 'efectivo'] ?? 'Efectivo' }}
                                    </span>
                                </td>
                                <td>
                                    @if($cobro->captura)
                                        <a href="{{ asset($cobro->captura) }}" target="_blank"
                                           class="btn btn-sm btn-outline-primary" style="font-size:0.72rem">
                                            <i class="fas fa-image"></i> Ver
                                        </a>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="text-end fw-bold text-success">S/ {{ number_format($cobro->monto,2) }}</td>
                                <td>
                                    <form method="POST" action="{{ route('cuotas.destroy', $cobro) }}"
                                          onsubmit="return confirm('¿Anular este cobro?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">Sin cobros registrados</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($cobros->hasPages())
                <div class="card-footer">{{ $cobros->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('sel-actividad').addEventListener('change', function() {
    var cuota = this.options[this.selectedIndex].dataset.cuota;
    if (cuota) document.getElementById('inp-monto').value = parseFloat(cuota).toFixed(2);
});

function toggleCaptura() {
    var metodo = document.getElementById('sel-metodo').value;
    var div = document.getElementById('div-captura');
    div.style.display = (metodo === 'yape' || metodo === 'plin' || metodo === 'otro') ? 'block' : 'none';
    document.getElementById('inp-captura').required = (metodo === 'yape' || metodo === 'plin');
}

function previewCaptura(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('captura-preview').innerHTML =
                '<img src="' + e.target.result + '" style="max-height:120px;border-radius:8px;border:2px solid #c8991a">';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
