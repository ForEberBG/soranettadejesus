<?php

namespace App\Http\Controllers;

use App\Models\Mesa;
use App\Models\Plato;
use App\Models\Venta;
use App\Models\Pedido;
use App\Models\Cliente;
use App\Models\CategoriaPlato;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartaController extends Controller
{
    public function index()
    {
        return view('carta.index');
    }

    public function mesa(Mesa $mesa)
    {
        return view('carta.index', compact('mesa'));
    }

    public function pedido(Request $request)
    {
        $request->validate([
            'mesa_id'        => 'required|exists:mesas,id',
            'nombre_cliente' => 'required|string',
            'platos'         => 'required|array',
            'platos.*.id'    => 'required|exists:platos,id',
            'platos.*.cantidad' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            // Buscar o crear cliente genérico
            // Generar documento único QR
            $ultimoQR = Cliente::where('documento', 'like', 'QR%')->count();
            $docQR = 'QR' . str_pad($ultimoQR + 1, 4, '0', STR_PAD_LEFT);

            $cliente = Cliente::firstOrCreate(
                ['nombre' => $request->nombre_cliente, 'documento' => $docQR],
                ['tipo_documento' => 'DNI', 'email' => '', 'telefono' => '']
            );
            // Calcular total
            $total = 0;
            $detalles = [];
            foreach ($request->platos as $item) {
                $plato    = Plato::findOrFail($item['id']);
                $subtotal = $plato->precio * $item['cantidad'];
                $total   += $subtotal;
                $detalles[] = [
                    'plato_id'        => $plato->id,
                    'cantidad'        => $item['cantidad'],
                    'precio_unitario' => $plato->precio,
                    'subtotal'        => $subtotal,
                ];
            }

            // Crear venta
            $venta = Venta::create([
                'mesa_id'      => $request->mesa_id,
                'usuario_id'   => 1, // admin por defecto
                'cliente_id'   => $cliente->id,
                'tipo'         => 'mesa',
                'total'        => $total,
                'metodo_pago'  => 'efectivo',
                'estado'       => 'pendiente',
                'fecha'        => now()->toDateString(),
                'estado_sunat' => 'pendiente',
            ]);

            foreach ($detalles as $d) {
                $venta->detalleVenta()->create($d);
            }

            // Número del día
            $hoy       = now()->toDateString();
            $ultimoNro = Pedido::where('fecha_dia', $hoy)->max('numero_dia') ?? 0;

            $pedido = Pedido::create([
                'venta_id'   => $venta->id,
                'estado'     => 'pendiente',
                'nota' => '[QR] ' . $request->nombre_cliente . ($request->nota ? ' – ' . $request->nota : ''),
                'numero_dia' => $ultimoNro + 1,
                'fecha_dia'  => $hoy,
            ]);

            Mesa::find($request->mesa_id)->update(['estado' => 'ocupada']);

            DB::commit();
            return response()->json(['success' => true, 'pedido_id' => $pedido->id]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'mensaje' => $e->getMessage()], 500);
        }
    }
}
