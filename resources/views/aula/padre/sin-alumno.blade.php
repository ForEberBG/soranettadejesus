@extends('layouts.app')
@section('title', 'Portal de Padres')

@section('content')
<div class="container-fluid">
    <div class="card" style="max-width:500px;margin:60px auto">
        <div class="card-body text-center py-5">
            <div style="font-size:3rem;margin-bottom:16px">⚠️</div>
            <h5 class="fw-bold" style="color:#1a5c2e">Sin alumno vinculado</h5>
            <p class="text-muted" style="font-size:0.9rem">
                Tu cuenta aún no tiene un alumno asignado.<br>
                Por favor comunícate con el docente o administrador del sistema.
            </p>
        </div>
    </div>
</div>
@endsection
