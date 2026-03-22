@include('aula.pdf.header')

<div class="titulo-reporte">📊 Reporte de Ingresos y Gastos</div>

{{-- Resumen financiero --}}
<div style="display:table; width:100%; margin-bottom:16px; border:1px solid #dde8df; border-radius:4px">
    <div style="display:table-cell; text-align:center; padding:12px; border-right:1px solid #dde8df">
        <div style="font-size:9px; color:#888; text-transform:uppercase">Total ingresos</div>
        <div style="font-size:18px; font-weight:bold; color:#1a5c2e">S/ {{ number_format($totalIngresos,2) }}</div>
    </div>
    <div style="display:table-cell; text-align:center; padding:12px; border-right:1px solid #dde8df">
        <div style="font-size:9px; color:#888; text-transform:uppercase">Total gastos</div>
        <div style="font-size:18px; font-weight:bold; color:#c0392b">S/ {{ number_format($totalGastos,2) }}</div>
    </div>
    <div style="display:table-cell; text-align:center; padding:12px; border-right:1px solid #dde8df">
        <div style="font-size:9px; color:#888; text-transform:uppercase">Utilidad neta</div>
        <div style="font-size:18px; font-weight:bold; color:{{ $utilidad >= 0 ? '#1a5c2e' : '#c0392b' }}">
            S/ {{ number_format($utilidad,2) }}
        </div>
    </div>
    <div style="display:table-cell; text-align:center; padding:12px">
        <div style="font-size:9px; color:#888; text-transform:uppercase">Meta total</div>
        <div style="font-size:18px; font-weight:bold; color:#c8991a">S/ {{ number_format($metaTotal,2) }}</div>
    </div>
</div>

{{-- Detalle por actividad --}}
<table>
    <thead>
        <tr>
            <th>Actividad</th>
            <th class="text-right">Cuota</th>
            <th class="text-right">Meta</th>
            <th class="text-right">Cobrado</th>
            <th class="text-right">Pendiente</th>
            <th class="text-right">Gastos</th>
            <th class="text-right">Utilidad</th>
            <th class="text-center">Avance</th>
        </tr>
    </thead>
    <tbody>
        @foreach($actividades as $act)
        @php
            $meta      = $act->cuota * $totalAlumnos;
            $cobrado   = $act->cobros_sum_monto ?? 0;
            $gastosAct = $act->gastos_sum_monto ?? 0;
            $pendiente = $meta - $cobrado;
            $util      = $cobrado - $gastosAct;
            $pct       = $meta > 0 ? min(100, round($cobrado / $meta * 100)) : 0;
        @endphp
        <tr>
            <td class="fw-bold">{{ $act->nombre }}</td>
            <td class="text-right">S/ {{ number_format($act->cuota,2) }}</td>
            <td class="text-right">S/ {{ number_format($meta,2) }}</td>
            <td class="text-right text-green fw-bold">S/ {{ number_format($cobrado,2) }}</td>
            <td class="text-right {{ $pendiente > 0 ? 'text-red' : 'text-green' }}">S/ {{ number_format($pendiente,2) }}</td>
            <td class="text-right text-red">S/ {{ number_format($gastosAct,2) }}</td>
            <td class="text-right fw-bold {{ $util >= 0 ? 'text-green' : 'text-red' }}">S/ {{ number_format($util,2) }}</td>
            <td class="text-center">{{ $pct }}%</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr class="total-row">
            <td class="fw-bold">TOTALES</td>
            <td></td>
            <td class="text-right fw-bold">S/ {{ number_format($metaTotal,2) }}</td>
            <td class="text-right fw-bold text-green">S/ {{ number_format($totalIngresos,2) }}</td>
            <td class="text-right fw-bold text-red">S/ {{ number_format($metaTotal - $totalIngresos,2) }}</td>
            <td class="text-right fw-bold text-red">S/ {{ number_format($totalGastos,2) }}</td>
            <td class="text-right fw-bold {{ $utilidad >= 0 ? 'text-green' : 'text-red' }}">S/ {{ number_format($utilidad,2) }}</td>
            <td></td>
        </tr>
    </tfoot>
</table>

<div class="footer">
    IE Sor Annetta de Jesús · Control de Aula · {{ now()->format('d/m/Y') }}
</div>
</body>
</html>
