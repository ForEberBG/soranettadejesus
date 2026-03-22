@include('aula.pdf.header')

<div class="titulo-reporte">📋 Lista de Alumnos del Aula</div>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Apellidos y Nombres</th>
            <th>DNI</th>
            <th>Sección</th>
            <th>Apoderado</th>
            <th>Parentesco</th>
            <th>Celular</th>
            <th>Estado</th>
        </tr>
    </thead>
    <tbody>
        @forelse($alumnos as $al)
        <tr>
            <td class="text-center">{{ $loop->iteration }}</td>
            <td class="fw-bold">{{ $al->nombre_completo }}</td>
            <td>{{ $al->dni ?? '—' }}</td>
            <td class="text-center">{{ $al->seccion ?? '—' }}</td>
            <td>{{ $al->apoderado ?? '—' }}</td>
            <td>{{ $al->parentesco ?? '—' }}</td>
            <td>{{ $al->celular ?? '—' }}</td>
            <td class="text-center">
                @if($al->deuda_total <= 0)
                    <span class="badge-green">Al día</span>
                @else
                    <span class="badge-red">Debe S/ {{ number_format($al->deuda_total,2) }}</span>
                @endif
            </td>
        </tr>
        @empty
        <tr><td colspan="8" class="text-center">Sin alumnos registrados</td></tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr class="total-row">
            <td colspan="7" class="fw-bold">Total alumnos</td>
            <td class="text-center fw-bold">{{ $alumnos->count() }}</td>
        </tr>
    </tfoot>
</table>

<div class="footer">
    IE Sor Annetta de Jesús · Control de Aula · {{ now()->format('d/m/Y') }}
</div>
</body>
</html>
