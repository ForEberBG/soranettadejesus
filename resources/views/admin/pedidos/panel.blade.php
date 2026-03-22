{{-- resources/views/admin/pedidos/panel.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container" style="padding: 20px;">

    <h1 class="titulo-panel">Panel de Pedidos</h1>

    @php
        $pendientes = $pedidos->where('estado', 'en preparacion')->count();
    @endphp

    @if($pendientes > 0)
        <div class="pedidos-pendientes">
            Pedidos pendientes: {{ $pendientes }}
        </div>
    @endif

    <div class="row">
        @foreach($pedidos as $pedido)
            <div class="col-md-4" style="margin-bottom: 20px;">
                <div class="card-pedido">
                    <h5 class="id-pedido">ID Pedido: {{ $pedido->id }}</h5>
                    <p><strong>Venta:</strong> {{ $pedido->venta_id }}</p>
                    <p>
                        <strong>Estado:</strong>
                        @if($pedido->estado == 'listo')
                            <span class="estado-listo">{{ $pedido->estado }}</span>
                        @elseif($pedido->estado == 'en preparacion')
                            <span class="estado-preparacion">{{ $pedido->estado }}</span>
                        @elseif($pedido->estado == 'entregado')
                            <span class="estado-entregado">{{ $pedido->estado }}</span>
                        @else
                            {{ $pedido->estado }}
                        @endif
                    </p>
                    @if($pedido->nota)
                        <p><strong>Nota:</strong> {{ $pedido->nota }}</p>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>

{{-- Estilos --}}
<style>
    body {
        background: url('{{ asset("storage/pedidos-bg.png") }}') no-repeat center center fixed;
        background-size: cover;
    }

    /* Título principal */
    .titulo-panel {
        color: #fff;
        text-shadow: 2px 2px 4px #000;
        margin-bottom: 20px;
    }

    /* Pedidos pendientes */
    .pedidos-pendientes {
        margin-bottom: 20px;
        color: #fff;
        font-weight: bold;
        font-size: 18px;
        text-shadow: 1px 1px 2px #000;
    }

    /* Card de cada pedido */
    .card-pedido {
        background-color: rgba(0,0,0,0.7);
        color: #fff;
        border-radius: 10px;
        padding: 15px;
        box-shadow: 2px 2px 10px #000;
        transition: transform 0.2s;
    }

    .card-pedido:hover {
        transform: scale(1.05);
    }

    /* ID Pedido destacado */
    .id-pedido {
        color: #00cfff;
        text-shadow: 2px 2px 4px #000;
        font-weight: bold;
        margin-bottom: 10px;
    }

    /* Estados */
    .estado-listo {
        color: #00ff00;
        font-weight: bold;
    }

    .estado-preparacion {
        color: #ffff00;
        font-weight: bold;
    }

    .estado-entregado {
        color: #00cfff;
        font-weight: bold;
    }
</style>
@endsection
