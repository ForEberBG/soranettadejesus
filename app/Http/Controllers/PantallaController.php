<?php
namespace App\Http\Controllers;

use App\Models\Pedido;

class PantallaController extends Controller
{
    public function index()
    {
        $pedidos = Pedido::with('venta.mesa', 'venta.detalleVenta.plato')
            ->whereNotIn('estado', ['cobrado', 'entregado'])
            ->orderBy('created_at', 'asc')
            ->get();

        return view('admin.pantalla.publica', compact('pedidos'));
    }

    public function pedidosJson()
    {
        $pedidos = Pedido::with('venta.mesa', 'venta.detalleVenta.plato')
            ->whereNotIn('estado', ['cobrado', 'entregado'])
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($pedidos);
    }
}
