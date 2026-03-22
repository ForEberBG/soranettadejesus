<?php
namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\Proveedor;
use App\Models\DetalleCompra;
use App\Models\Ingrediente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompraController extends Controller
{
    // Vista para crear una compra
    public function create()
    {
        $proveedores = Proveedor::all(); // Obtener proveedores
        $ingredientes = Ingrediente::all(); // Obtener ingredientes
        return view('admin.compras.create', compact('proveedores', 'ingredientes'));
    }

    // Almacenar la compra
    public function store(Request $request)
{
    // Validación
    $request->validate([
        'proveedor_id' => 'required|exists:proveedores,id',
        'fecha' => 'required|date',
        'ingrediente_id' => 'required|array',
        'ingrediente_id.*' => 'exists:ingredientes,id',
        'cantidad' => 'required|array',
        'cantidad.*' => 'numeric|min:1',
        'precio_unitario' => 'required|array',
        'precio_unitario.*' => 'numeric|min:0',
        'subtotal' => 'required|array',
        'subtotal.*' => 'numeric|min:0'
    ]);

    // Iniciar una transacción
    DB::beginTransaction();

    try {
        // Crear la compra
        $compra = Compra::create([
            'proveedor_id' => $request->proveedor_id,
            'usuario_id' => auth()->user()->id,
            'fecha' => $request->fecha,
            'total' => array_sum($request->subtotal), // Total de la compra
        ]);

        // Registrar el detalle de la compra y actualizar el stock
        foreach ($request->ingrediente_id as $index => $ingredienteId) {
            // Registrar detalle de compra
            DetalleCompra::create([
                'compra_id' => $compra->id,
                'ingrediente_id' => $ingredienteId,
                'cantidad' => $request->cantidad[$index],
                'precio_unitario' => $request->precio_unitario[$index],
                'subtotal' => $request->subtotal[$index],
            ]);

            // Actualizar el stock del ingrediente
            $ingrediente = Ingrediente::find($ingredienteId);
            $ingrediente->stock += $request->cantidad[$index]; // Aumentar el stock
            $ingrediente->save();
        }

        // Confirmar la transacción
        DB::commit();
        return redirect()->route('admin.compras.index')->with('success', 'Compra registrada correctamente.');
    } catch (\Exception $e) {
        // Si ocurre un error, revertimos la transacción
        DB::rollBack();
        return redirect()->back()->with('error', 'Error al registrar la compra: ' . $e->getMessage());
    }
}

    // Mostrar todas las compras
    public function index()
    {
        $compras = Compra::with('proveedor', 'detalleCompra.ingrediente')->get();
        return view('admin.compras.index', compact('compras'));
    }

    // Editar una compra
    public function edit(Compra $compra)
    {
        $proveedores = Proveedor::all();
        $ingredientes = Ingrediente::all();
        return view('admin.compras.edit', compact('compra', 'proveedores', 'ingredientes'));
    }

    // Actualizar una compra
    public function update(Request $request, Compra $compra)
    {
        $request->validate([
            'proveedor_id' => 'required|exists:proveedores,id',
            'usuario_id' => 'required|exists:users,id',
            'fecha' => 'required|date',
            'ingrediente_id' => 'required|array',
            'ingrediente_id.*' => 'exists:ingredientes,id',
            'cantidad' => 'required|array',
            'cantidad.*' => 'numeric|min:1',
            'precio_unitario' => 'required|array',
            'precio_unitario.*' => 'numeric|min:0',
            'subtotal' => 'required|array',
            'subtotal.*' => 'numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $compra->update([
                'proveedor_id' => $request->proveedor_id,
                'usuario_id' => $request->usuario_id,
                'fecha' => $request->fecha,
                'total' => array_sum($request->subtotal),
            ]);

            $compra->detalleCompra()->delete();

            // Registrar los nuevos detalles de la compra
            foreach ($request->ingrediente_id as $index => $ingredienteId) {
                DetalleCompra::create([
                    'compra_id' => $compra->id,
                    'ingrediente_id' => $ingredienteId,
                    'cantidad' => $request->cantidad[$index],
                    'precio_unitario' => $request->precio_unitario[$index],
                    'subtotal' => $request->subtotal[$index],
                ]);
            }

            DB::commit();
            return redirect()->route('admin.compras.index')->with('success', 'Compra actualizada correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al actualizar la compra: ' . $e->getMessage());
        }
    }

    // Eliminar una compra
    public function destroy(Compra $compra)
    {
        DB::beginTransaction();

        try {
            $compra->detalleCompra()->delete();
            $compra->delete();

            DB::commit();
            return redirect()->route('admin.compras.index')->with('success', 'Compra eliminada correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al eliminar la compra: ' . $e->getMessage());
        }
    }
}
