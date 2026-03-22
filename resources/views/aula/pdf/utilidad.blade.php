@include('aula.pdf.header')

<div class="titulo-reporte">📈 Reporte General de Utilidad</div>

{{-- Resumen --}}
<div style="display:table; width:100%; margin-bottom:16px">
    <div style="display:table-cell; padding:10px; border:2px solid #1a5c2e; border-radius:4px; text-align:center; margin:0 4px">
        <div style="font-size:9px; color:#888">INGRESOS</div>
        <div style="font-size:20px; font-weight:bold; color:#1a5c2e">S/ {{ number_format($totalIngresos,2) }}</div>
    </div>
    <div style="display:table-cell; padding:4px"></div>
    <div style="display:table-cell; padding:10px; border:2px solid #c0392b; border-radius:4px; text-align:center">
        <div style="font-size:9px; color:#888">GASTOS</div>
        <div style="font-size:20px; font-weight:bold; color:#c0392b">S/ {{ number_format($totalGastos,2) }}</div>
    </div>
    <div style="display:table-cell; padding:4px"></div>
    <div style="display:table-cell; padding:10px; border:2px solid {{ $utilidad >= 0 ? '#1a5c2e' : '#c0392b' }}; border-radius:4px; text-align:center">
        <div style="font-size:9px; color:#888">UTILIDAD NETA</div>
        <div style="font-size:20px; font-weight:bold; color:{{ $utilidad >= 0 ? '#1a5c2e' : '#c0392b' }}">
            S/ {{ number_format($utilidad,2) }}
        </div>
        <div style="font-size:8px; color:#888">{{ $utilidad >= 0 ? 'Superávit' : 'Déficit' }}</div>
    </div>
</div>

{{-- Cobros recientes --}}
<div style="font-weight:bold; color:#1a5c2e; margin-bottom:6px; font-size:11px; border-bottom:1px solid #dde8df; padding-bottom:4px">
    Últimos cobros registrados
</div>
<table style="margin-bottom:14px">
    <thead>
        <tr>
            <th>Fecha</th>
            <th>Alumno</th>
            <th>Actividad</th>
            <th>Método</th>
            <th class="text-right">Monto</th>
        </tr>
    </thead>
    <tbody>
        @forelse($cobros->take(10) as $cobro)
        <tr>
            <td>{{ \Carbon\Carbon::parse($cobro->fecha)->format('d/m/Y') }}</td>
            <td class="fw-bold">{{ $cobro->alumno->nombre_completo }}</td>
            <td>{{ $cobro->actividad->nombre }}</td>
            <td><span class="badge-green">{{ ucfirst($cobro->metodo_pago ?? 'efectivo') }}</span></td>
            <td class="text-right fw-bold text-green">S/ {{ number_format($cobro->monto,2) }}</td>
        </tr>
        @empty
        <tr><td colspan="5" class="text-center">Sin cobros</td></tr>
        @endforelse
    </tbody>
</table>

{{-- Gastos recientes --}}
<div style="font-weight:bold; color:#c0392b; margin-bottom:6px; font-size:11px; border-bottom:1px solid #dde8df; padding-bottom:4px">
    Últimos gastos registrados
</div>
<table>
    <thead>
        <tr>
            <th>Fecha</th>
            <th>Descripción</th>
            <th>Categoría</th>
            <th>Actividad</th>
            <th class="text-right">Monto</th>
        </tr>
    </thead>
    <tbody>
        @forelse($gastos->take(10) as $gasto)
        <tr>
            <td>{{ \Carbon\Carbon::parse($gasto->fecha)->format('d/m/Y') }}</td>
            <td class="fw-bold">{{ $gasto->descripcion }}</td>
            <td><span class="badge-gold">{{ $gasto->categoria }}</span></td>
            <td>{{ $gasto->actividad->nombre ?? 'General' }}</td>
            <td class="text-right fw-bold text-red">S/ {{ number_format($gasto->monto,2) }}</td>
        </tr>
        @empty
        <tr><td colspan="5" class="text-center">Sin gastos</td></tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr class="total-row">
            <td colspan="4" class="fw-bold">TOTAL GASTOS</td>
            <td class="text-right fw-bold text-red">S/ {{ number_format($totalGastos,2) }}</td>
        </tr>
    </tfoot>
</table>

<div class="footer">
    IE Sor Annetta de Jesús · Control de Aula · {{ now()->format('d/m/Y') }}
</div>
</body>
</html>
