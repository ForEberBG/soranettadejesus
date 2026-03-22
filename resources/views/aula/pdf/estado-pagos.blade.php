@include('aula.pdf.header')

<div class="titulo-reporte">💰 Estado de Pagos por Alumno</div>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Alumno</th>
            @foreach($actividades as $act)
                <th class="text-center" style="font-size:8px">{{ $act->nombre }}<br>S/ {{ number_format($act->cuota,2) }}</th>
            @endforeach
            <th class="text-center">Total pagado</th>
            <th class="text-center">Deuda</th>
        </tr>
    </thead>
    <tbody>
        @foreach($alumnos as $al)
        @php
            $totalPagado = 0;
            $deudaTotal  = 0;
        @endphp
        <tr>
            <td class="text-center">{{ $loop->iteration }}</td>
            <td class="fw-bold">{{ $al->nombre_completo }}</td>
            @foreach($actividades as $act)
                @php
                    $cobro = $cobros->where('alumno_id', $al->id)->where('actividad_id', $act->id)->first();
                    $totalPagado += $cobro ? $cobro->monto : 0;
                    $deudaTotal  += $cobro ? 0 : $act->cuota;
                @endphp
                <td class="text-center">
                    @if($cobro)
                        <span class="badge-green">✓</span>
                    @else
                        <span class="badge-red">✗</span>
                    @endif
                </td>
            @endforeach
            <td class="text-center text-green fw-bold">S/ {{ number_format($totalPagado,2) }}</td>
            <td class="text-center {{ $deudaTotal > 0 ? 'text-red' : 'text-green' }} fw-bold">
                {{ $deudaTotal > 0 ? 'S/ '.number_format($deudaTotal,2) : 'Al día ✓' }}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="footer">
    IE Sor Annetta de Jesús · Control de Aula · {{ now()->format('d/m/Y') }}
</div>
</body>
</html>
