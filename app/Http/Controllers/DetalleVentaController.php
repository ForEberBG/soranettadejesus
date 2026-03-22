<?php
namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\Plato;
use Illuminate\Http\Request;

class DetalleVentaController extends Controller
{
    // Agregar un producto a la venta
    public function store(Request $request, $ventaId)
    {
        $request->validate([
            'plato_id' => 'required|exists:platos,id',
            'cantidad' => 'required|integer|min:1',
        ]);

        // Obtener la venta y los platos
        $venta = Venta::findOrFail($ventaId);
        $plato = Plato::findOrFail($request->plato_id);

        // Calcular el precio total del producto (precio unitario * cantidad)
        $precioTotal = $plato->precio * $request->cantidad;

        // Crear el detalle de la venta
        DetalleVenta::create([
            'venta_id' => $venta->id,
            'plato_id' => $plato->id,
            'cantidad' => $request->cantidad,
            'precio_unitario' => $plato->precio,
            'subtotal' => $precioTotal,
        ]);

        // Actualizar el precio total de la venta
        $venta->total += $precioTotal;
        $venta->save();

        return redirect()->route('admin.ventas.edit', $ventaId)->with('success', 'Producto agregado a la venta.');
    }

    // Eliminar un producto de la venta
    public function destroy($ventaId, $detalleVentaId)
    {
        // Obtener la venta y el detalle de la venta
        $venta = Venta::findOrFail($ventaId);
        $detalleVenta = DetalleVenta::findOrFail($detalleVentaId);

        // Eliminar el detalle de la venta
        $detalleVenta->delete();

        // Actualizar el total de la venta
        $venta->total -= $detalleVenta->sub_total;
        $venta->save();

        return redirect()->route('admin.ventas.edit', $ventaId)->with('success', 'Producto eliminado de la venta.');
    }
}
