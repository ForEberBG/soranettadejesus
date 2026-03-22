<?php
namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Venta;
use Illuminate\Http\Request;

class CocinaController extends Controller
{
    public function panel()
    {
        $pedidos = Pedido::with('venta.mesa', 'venta.detalleVenta.plato')
            ->whereIn('estado', ['pendiente', 'en preparacion'])
            ->orderBy('created_at', 'asc') // orden por llegada
            ->get();

        $historial = Pedido::with('venta.mesa', 'venta.detalleVenta.plato')
            ->whereIn('estado', ['listo', 'entregado','cobrado'])
            ->orderBy('updated_at', 'desc')
            ->take(20)
            ->get();

        return view('admin.cocina.panel', compact('pedidos', 'historial'));
    }

    public function cambiarEstado(Request $request, Pedido $pedido)
    {
        $request->validate([
            'estado' => 'required|in:pendiente,en preparacion,listo,entregado'
        ]);

        $pedido->update(['estado' => $request->estado]);

        // Notificar al mozo si está listo
        if ($request->estado === 'listo') {
            $mozo = $pedido->venta->usuario;
            if ($mozo && $mozo->hasRole('mozo')) {
                $mozo->notify(new \App\Notifications\PedidoListoNotification($pedido));
            }
        }

        return response()->json([
            'success' => true,
            'mensaje' => 'Estado actualizado: ' . $request->estado
        ]);
    }

    public function pedidosNuevos()
    {
        $pedidos = Pedido::with('venta.mesa', 'venta.detalleVenta.plato')
            ->whereIn('estado', ['pendiente', 'en preparacion'])
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($pedidos);
    }
}
