@include('aula.pdf.header')

<div class="titulo-reporte">🤝 Lista de Asistencia — Reunión de Padres</div>

<table style="margin-bottom:14px; font-size:10px; width:auto">
    <tr>
        <td style="padding:4px 8px; color:#888">Tema:</td>
        <td style="padding:4px 8px; font-weight:bold">{{ $reunion->tema }}</td>
        <td style="padding:4px 8px; color:#888">Fecha:</td>
        <td style="padding:4px 8px; font-weight:bold">{{ \Carbon\Carbon::parse($reunion->fecha)->format('d/m/Y') }}</td>
        <td style="padding:4px 8px; color:#888">Hora:</td>
        <td style="padding:4px 8px; font-weight:bold">{{ $reunion->hora }}</td>
    </tr>
    <tr>
        <td style="padding:4px 8px; color:#888">Lugar:</td>
        <td style="padding:4px 8px; font-weight:bold" colspan="5">{{ $reunion->lugar ?? '—' }}</td>
    </tr>
</table>

@php
    $asistencias = $reunion->asistencias->keyBy('alumno_id');
    $asistieron  = $asistencias->where('asistio', true)->count();
@endphp

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Apellidos y Nombres del Alumno</th>
            <th>Apoderado</th>
            <th>Celular</th>
            <th class="text-center">Asistencia</th>
            <th>Firma</th>
        </tr>
    </thead>
    <tbody>
        @foreach($alumnos as $al)
        @php $asistio = isset($asistencias[$al->id]) && $asistencias[$al->id]->asistio; @endphp
        <tr>
            <td class="text-center">{{ $loop->iteration }}</td>
            <td class="fw-bold">{{ $al->nombre_completo }}</td>
            <td>{{ $al->apoderado ?? '—' }}</td>
            <td>{{ $al->celular ?? '—' }}</td>
            <td class="text-center">
                @if($asistio)
                    <span class="badge-green">✓ Asistió</span>
                @else
                    <span class="badge-red">Ausente</span>
                @endif
            </td>
            <td style="border-bottom:1px solid #ccc; min-width:80px">&nbsp;</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr class="total-row">
            <td colspan="4" class="fw-bold">Resumen</td>
            <td class="text-center fw-bold text-green">{{ $asistieron }} / {{ $alumnos->count() }}</td>
            <td></td>
        </tr>
    </tfoot>
</table>

@if($reunion->notas)
<div style="margin-top:16px; padding:10px; background:#f5f7f5; border-left:3px solid #1a5c2e; border-radius:0 4px 4px 0">
    <div style="font-size:9px; color:#888; text-transform:uppercase; margin-bottom:4px">Acuerdos / Notas</div>
    <div style="font-size:10px">{{ $reunion->notas }}</div>
</div>
@endif

<div style="margin-top:30px; display:table; width:100%">
    <div style="display:table-cell; text-align:center; padding:0 20px">
        <div style="border-top:1px solid #333; padding-top:6px; font-size:9px; color:#555">
            Firma del Docente<br>{{ $config->docente ?? '____________________' }}
        </div>
    </div>
    <div style="display:table-cell; text-align:center; padding:0 20px">
        <div style="border-top:1px solid #333; padding-top:6px; font-size:9px; color:#555">
            Sello del Aula
        </div>
    </div>
</div>

<div class="footer">
    IE Sor Annetta de Jesús · Control de Aula · {{ now()->format('d/m/Y') }}
</div>
</body>
</html>
