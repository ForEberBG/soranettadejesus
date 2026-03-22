@include('aula.pdf.header')

<div class="titulo-reporte">🧾 Gastos Detallados</div>

{{-- Resumen por categoría --}}
<div style="margin-bottom:14px; display:table; width:100%">
    @foreach($porCat as $cat => $items)
    @php $subtotal = $items->sum('monto'); @endphp
    <div style="display:table-cell; text-align:center; padding:6px 4px; border:1px solid #dde8df">
        <div style="font-size:9px; color:#888">{{ $cat }}</div>
        <div style="font-weight:bold; color:#c8991a; font-size:12px">S/ {{ number_format($subtotal,2) }}</div>
    </div>
    @endforeach
</div>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Fecha</th>
            <th>Descripción</th>
            <th>Categoría</th>
            <th>Actividad</th>
            <th>Comprobante</th>
            <th class="text-right">Monto</th>
        </tr>
    </thead>
    <tbody>
        @forelse($gastos as $gasto)
        <tr>
            <td class="text-center">{{ $loop->iteration }}</td>
            <td>{{ \Carbon\Carbon::parse($gasto->fecha)->format('d/m/Y') }}</td>
            <td class="fw-bold">{{ $gasto->descripcion }}</td>
            <td><span class="badge-gold">{{ $gasto->categoria }}</span></td>
            <td>{{ $gasto->actividad->nombre ?? 'General' }}</td>
            <td>{{ $gasto->comprobante ?? '—' }}</td>
            <td class="text-right fw-bold text-red">S/ {{ number_format($gasto->monto,2) }}</td>
        </tr>
        @empty
        <tr><td colspan="7" class="text-center">Sin gastos registrados</td></tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr class="total-row">
            <td colspan="6" class="fw-bold">TOTAL GASTOS</td>
            <td class="text-right fw-bold text-red">S/ {{ number_format($total,2) }}</td>
        </tr>
    </tfoot>
</table>

<div class="footer">
    IE Sor Annetta de Jesús · Control de Aula · {{ now()->format('d/m/Y') }}
</div>
</body>
</html>
