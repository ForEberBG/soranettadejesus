<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Notifications\PedidoListoNotification;
use App\Models\User;

class PedidoController extends Controller
{
    // Mostrar todos los pedidos
    public function index()
    {
        $pedidos = Pedido::with('venta.detalleVenta.plato')->get();  // Obtener los pedidos con su venta relacionada
        return view('admin.pedidos.index', compact('pedidos'));
    }

    public function show(Pedido $pedido)
    {
        $pedido->load('venta.detalleVenta.plato');
        return view('admin.pedidos.show', compact('pedido'));
    }
    // Vista para crear un nuevo pedido
    public function create()
    {
        $ventas = Venta::all();  // Mejor para rendimiento
        return view('admin.pedidos.create', compact('ventas'));
    }

    // Almacenar el nuevo pedido
    public function store(Request $request)
    {
        $request->validate([
            'venta_id' => 'required|exists:ventas,id',
            'estado' => 'required|in:pendiente,en preparacion,listo,entregado',
            'nota' => 'nullable|string',
        ]);
        $data = $request->all();

        try {
            Pedido::create($data);
            return redirect()->route('admin.pedidos.index')->with('success', 'Pedido registrado correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al registrar el pedido: ' . $e->getMessage());
        }
    }

    // Vista para editar un pedido
    public function edit(Pedido $pedido)
    {
        $ventas = Venta::select('id')->get();  // Mejor para rendimiento
        return view('admin.pedidos.edit', compact('pedido', 'ventas'));
    }

    // Actualizar un pedido
    // public function update(Request $request, Pedido $pedido)
    // {
    //     $request->validate([
    //         'venta_id' => 'required|exists:ventas,id',
    //         'estado' => 'required|in:pendiente,en preparacion,listo,entregado',
    //         'nota' => 'nullable|string',
    //     ]);
    //     $data = $request->all();

    //     try {
    //         $pedido->update($data);
    //         return redirect()->route('admin.pedidos.index')->with('success', 'Pedido actualizado correctamente.');
    //     } catch (\Exception $e) {
    //         return redirect()->back()->with('error', 'Error al actualizar el pedido: ' . $e->getMessage());
    //     }
    // }

    // // Eliminar un pedido
    // public function destroy(Pedido $pedido)
    // {
    //     try {
    //         $pedido->delete();
    //         return redirect()->route('admin.pedidos.index')->with('success', 'Pedido eliminado correctamente.');
    //     } catch (\Exception $e) {
    //         return redirect()->back()->with('error', 'Error al eliminar el pedido: ' . $e->getMessage());
    //     }
    // }

    public function update(Request $request, Pedido $pedido)
    {
        $request->validate([
            'venta_id' => 'required|exists:ventas,id',
            'estado' => 'required|in:pendiente,en preparacion,listo,entregado',
            'nota' => 'nullable|string',
        ]);

        $data = $request->all();

        try {
            $pedido->update($data);

            // ✅ Enviar notificación si el pedido está listo
            if ($data['estado'] === 'listo') {
                $mozo = $pedido->venta->usuario;
                if ($mozo && $mozo->hasRole('mozo')) { // Si usas roles
                    $mozo->notify(new \App\Notifications\PedidoListoNotification($pedido));
                }
            }

            return redirect()->route('admin.pedidos.index')->with('success', 'Pedido actualizado correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al actualizar el pedido: ' . $e->getMessage());
        }
    }

    public function panel()
    {
        $user = auth()->user();

        if (!$user->hasPermissionTo('ver_panel_mozo')) {
            abort(403, 'No autorizado.');
        }

        // Lógica normal del panel
        $pedidos = \App\Models\Pedido::with('venta.detalleVenta.plato')->get();
        return view('admin.pedidos.panel', compact('pedidos'));
    }
}
