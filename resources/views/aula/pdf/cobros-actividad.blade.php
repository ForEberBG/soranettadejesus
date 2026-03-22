@include('aula.pdf.header')

<div class="titulo-reporte">📅 Cobros por Actividad</div>

@foreach($actividades as $act)
@php
    $cobrosAct = $cobros->where('actividad_id', $act->id);
    $meta      = $act->cuota * $totalAlumnos;
    $cobrado   = $cobrosAct->sum('monto');
    $pct       = $meta > 0 ? round($cobrado / $meta * 100) : 0;
@endphp
<div style="margin-bottom:16px">
    <div style="background:#e8f5ec; padding:6px 10px; border-left:3px solid #1a5c2e; margin-bottom:6px; display:table; width:100%">
        <div style="display:table-cell; font-weight:bold; color:#1a5c2e">{{ $act->nombre }}</div>
        <div style="display:table-cell; text-align:right; font-size:9px; color:#555">
            Cuota: S/ {{ number_format($act->cuota,2) }} ·
            Cobrado: S/ {{ number_format($cobrado,2) }} /
            S/ {{ number_format($meta,2) }} ({{ $pct }}%)
        </div>
    </div>
    @if($cobrosAct->count() > 0)
    <table style="margin-bottom:4px">
        <thead>
            <tr>
                <th>#</th>
                <th>Fecha</th>
                <th>Alumno</th>
                <th>Método de pago</th>
                <th>Observaciones</th>
                <th class="text-right">Monto</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cobrosAct as $cobro)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ \Carbon\Carbon::parse($cobro->fecha)->format('d/m/Y') }}</td>
                <td class="fw-bold">{{ $cobro->alumno->nombre_completo }}</td>
                <td class="text-center">
                    <span class="badge-green">{{ ucfirst($cobro->metodo_pago ?? 'efectivo') }}</span>
                </td>
                <td>{{ $cobro->observaciones ?? '—' }}</td>
                <td class="text-right fw-bold text-green">S/ {{ number_format($cobro->monto,2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="5" class="fw-bold">Subtotal {{ $act->nombre }}</td>
                <td class="text-right fw-bold text-green">S/ {{ number_format($cobrado,2) }}</td>
            </tr>
        </tfoot>
    </table>
    @else
        <div style="text-align:center; color:#aaa; padding:8px; font-size:10px">Sin cobros registrados para esta actividad</div>
    @endif
</div>
@endforeach

<div style="background:#1a5c2e; color:#f0c040; padding:8px 10px; margin-top:10px; display:table; width:100%">
    <div style="display:table-cell; font-weight:bold">TOTAL GENERAL COBRADO</div>
    <div style="display:table-cell; text-align:right; font-weight:bold; font-size:13px">
        S/ {{ number_format($cobros->sum('monto'),2) }}
    </div>
</div>

<div class="footer">
    IE Sor Annetta de Jesús · Control de Aula · {{ now()->format('d/m/Y') }}
</div>
</body>
</html>
