@include('aula.pdf.header')

<div class="titulo-reporte">💵 Reporte de Caja Chica</div>

{{-- Info de la caja --}}
<table style="margin-bottom:14px;font-size:10px;width:auto">
    <tr>
        <td style="padding:4px 8px;color:#888">Apertura:</td>
        <td style="padding:4px 8px;font-weight:bold">{{ $caja->fecha_apertura ? $caja->fecha_apertura->format('d/m/Y H:i') : '—' }}</td>
        <td style="padding:4px 8px;color:#888">Cierre:</td>
        <td style="padding:4px 8px;font-weight:bold">{{ $caja->fecha_cierre ? $caja->fecha_cierre->format('d/m/Y H:i') : 'Abierta' }}</td>
    </tr>
    <tr>
        <td style="padding:4px 8px;color:#888">Descripción:</td>
        <td colspan="3" style="padding:4px 8px;font-weight:bold">{{ $caja->descripcion ?? '—' }}</td>
    </tr>
</table>

{{-- Resumen --}}
<div style="display:table;width:100%;margin-bottom:14px">
    <div style="display:table-cell;text-align:center;padding:10px;border:1px solid #dde8df">
        <div style="font-size:9px;color:#888">Monto inicial</div>
        <div style="font-size:16px;font-weight:bold;color:#c8991a">S/ {{ number_format($caja->monto_inicial,2) }}</div>
    </div>
    <div style="display:table-cell;text-align:center;padding:10px;border:1px solid #dde8df">
        <div style="font-size:9px;color:#888">Total egresos</div>
        <div style="font-size:16px;font-weight:bold;color:#c0392b">S/ {{ number_format($caja->total_egresos,2) }}</div>
    </div>
    <div style="display:table-cell;text-align:center;padding:10px;border:1px solid #dde8df">
        <div style="font-size:9px;color:#888">Reposiciones</div>
        <div style="font-size:16px;font-weight:bold;color:#1a5c2e">S/ {{ number_format($caja->total_reposiciones,2) }}</div>
    </div>
    <div style="display:table-cell;text-align:center;padding:10px;border:2px solid #1a5c2e">
        <div style="font-size:9px;color:#888">Saldo final</div>
        <div style="font-size:16px;font-weight:bold;color:{{ $caja->saldo_actual > 0 ? '#1a5c2e' : '#c0392b' }}">
            S/ {{ number_format($caja->saldo_actual,2) }}
        </div>
    </div>
</div>

{{-- Movimientos --}}
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Fecha</th>
            <th>Descripción</th>
            <th>Categoría</th>
            <th>Comprobante</th>
            <th>Tipo</th>
            <th class="text-right">Monto</th>
            <th class="text-right">Saldo</th>
        </tr>
    </thead>
    <tbody>
        @php $saldo = $caja->monto_inicial; @endphp
        @forelse($caja->movimientos->sortBy('fecha') as $mov)
        @php
            if($mov->tipo === 'egreso')      $saldo -= $mov->monto;
            else                              $saldo += $mov->monto;
        @endphp
        <tr>
            <td class="text-center">{{ $loop->iteration }}</td>
            <td>{{ \Carbon\Carbon::parse($mov->fecha)->format('d/m/Y') }}</td>
            <td class="fw-bold">{{ $mov->descripcion }}</td>
            <td><span class="badge-gold">{{ $mov->categoria }}</span></td>
            <td>{{ $mov->comprobante ?? '—' }}</td>
            <td class="text-center">
                @if($mov->tipo === 'egreso')
                    <span class="badge-red">Egreso</span>
                @else
                    <span class="badge-green">Reposición</span>
                @endif
            </td>
            <td class="text-right fw-bold {{ $mov->tipo === 'egreso' ? 'text-red' : 'text-green' }}">
                {{ $mov->tipo === 'egreso' ? '–' : '+' }}S/ {{ number_format($mov->monto,2) }}
            </td>
            <td class="text-right fw-bold {{ $saldo > 0 ? 'text-green' : 'text-red' }}">
                S/ {{ number_format($saldo,2) }}
            </td>
        </tr>
        @empty
        <tr><td colspan="8" class="text-center">Sin movimientos</td></tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr class="total-row">
            <td colspan="6" class="fw-bold">SALDO FINAL</td>
            <td></td>
            <td class="text-right fw-bold {{ $caja->saldo_actual > 0 ? 'text-green' : 'text-red' }}">
                S/ {{ number_format($caja->saldo_actual,2) }}
            </td>
        </tr>
    </tfoot>
</table>

<div style="margin-top:30px;display:table;width:100%">
    <div style="display:table-cell;text-align:center;padding:0 20px">
        <div style="border-top:1px solid #333;padding-top:6px;font-size:9px;color:#555">
            Responsable de caja<br>{{ $config->docente ?? '____________________' }}
        </div>
    </div>
    <div style="display:table-cell;text-align:center;padding:0 20px">
        <div style="border-top:1px solid #333;padding-top:6px;font-size:9px;color:#555">
            Visto bueno
        </div>
    </div>
</div>

<div class="footer">
    IE Sor Annetta de Jesús · Control de Aula · {{ now()->format('d/m/Y') }}
</div>
</body>
</html>
