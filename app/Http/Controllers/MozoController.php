<?php

namespace App\Http\Controllers;

use App\Models\Mesa;
use App\Models\Pedido;
use App\Models\Venta;
use App\Models\Cliente;
use App\Models\Plato;
use App\Models\DetalleVenta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MozoController extends Controller
{
    public function panel()
    {
        $mesas = Mesa::orderBy('numero')->get();
        $clientes = Cliente::orderBy('nombre')->get();
        $categorias = \App\Models\CategoriaPlato::with(['platos' => function ($q) {
            $q->where('estado', 'disponible')->orderBy('nombre');
        }])->get();

        $pedidos = Pedido::with('venta.mesa', 'venta.detalleVenta.plato')
            ->where(function ($q) {
                $q->whereHas('venta', function ($v) {
                    $v->where('usuario_id', auth()->id());
                })
                    ->orWhere('nota', 'like', '[QR]%'); // pedidos desde celular
            })
            ->whereNotIn('estado', ['cobrado'])
            ->orderBy('created_at', 'asc')
            ->get();

        return view('admin.mozo.panel', compact('mesas', 'clientes', 'categorias', 'pedidos'));
    }

    public function mesas()
    {
        $mesas = Mesa::withCount(['ventas' => function ($q) {
            $q->where('estado', 'pendiente');
        }])->get();
        return response()->json($mesas);
    }

    public function crearPedido(Request $request)
    {
        $request->validate([
            'mesa_id'           => 'required|exists:mesas,id',
            'cliente_id'        => 'required|exists:clientes,id',
            'platos'            => 'required|array',
            'platos.*.id'       => 'required|exists:platos,id',
            'platos.*.cantidad' => 'required|integer|min:1',
            'nota'              => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Buscar pedido activo de esta mesa
            $pedidoExistente = Pedido::whereHas('venta', function ($q) use ($request) {
                $q->where('mesa_id', $request->mesa_id)
                    ->where('usuario_id', auth()->id());
            })->whereNotIn('estado', ['cobrado'])->latest()->first();

            if ($pedidoExistente) {
                // ── AGREGAR platos al pedido existente ──
                $venta = $pedidoExistente->venta;
                $totalExtra = 0;

                foreach ($request->platos as $item) {
                    $plato    = Plato::findOrFail($item['id']);
                    $subtotal = $plato->precio * $item['cantidad'];
                    $totalExtra += $subtotal;

                    // Si ya existe el plato en el detalle, sumar cantidad
                    $detalle = $venta->detalleVenta()->where('plato_id', $plato->id)->first();
                    if ($detalle) {
                        $detalle->update([
                            'cantidad' => $detalle->cantidad + $item['cantidad'],
                            'subtotal' => $detalle->subtotal + $subtotal,
                        ]);
                    } else {
                        $venta->detalleVenta()->create([
                            'plato_id'        => $plato->id,
                            'cantidad'        => $item['cantidad'],
                            'precio_unitario' => $plato->precio,
                            'subtotal'        => $subtotal,
                        ]);
                    }
                }

                // Actualizar total de la venta
                $venta->update(['total' => $venta->total + $totalExtra]);

                // Actualizar nota si hay nueva
                if ($request->nota) {
                    $pedidoExistente->update([
                        'nota' => $pedidoExistente->nota
                            ? $pedidoExistente->nota . ' | ' . $request->nota
                            : $request->nota
                    ]);
                }

                // Reenviar a cocina si ya estaba listo/entregado
                if (in_array($pedidoExistente->estado, ['listo', 'entregado'])) {
                    $pedidoExistente->update(['estado' => 'en preparacion']);
                }

                DB::commit();
                return response()->json([
                    'success'   => true,
                    'mensaje'   => 'Platos agregados al pedido existente',
                    'pedido_id' => $pedidoExistente->id,
                ]);
            } else {
                // ── CREAR pedido nuevo ──
                $total    = 0;
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

                $venta = Venta::create([
                    'mesa_id'      => $request->mesa_id,
                    'usuario_id'   => auth()->id(),
                    'cliente_id'   => $request->cliente_id,
                    'tipo'         => 'mesa',
                    'total'        => $total,
                    'metodo_pago'  => 'efectivo',
                    'estado'       => 'pendiente',
                    'fecha'        => now()->toDateString(),
                    'estado_sunat' => 'pendiente',
                ]);

                foreach ($detalles as $detalle) {
                    $venta->detalleVenta()->create($detalle);
                }

                // Calcular número del día
                $hoy = now()->toDateString();
                $ultimoNro = Pedido::where('fecha_dia', $hoy)->max('numero_dia') ?? 0;

                $pedido = Pedido::create([
                    'venta_id'   => $venta->id,
                    'estado'     => 'pendiente',
                    'nota'       => $request->nota,
                    'numero_dia' => $ultimoNro + 1,
                    'fecha_dia'  => $hoy,
                ]);

                Mesa::find($request->mesa_id)->update(['estado' => 'ocupada']);

                DB::commit();
                return response()->json([
                    'success'   => true,
                    'mensaje'   => 'Pedido enviado a cocina',
                    'pedido_id' => $pedido->id,
                ]);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'mensaje' => $e->getMessage()], 500);
        }
    }

    public function estadoPedidos()
    {
        $pedidos = Pedido::with('venta.mesa', 'venta.detalleVenta.plato')
            ->where(function ($q) {
                $q->whereHas('venta', function ($v) {
                    $v->where('usuario_id', auth()->id());
                })
                    ->orWhere('nota', 'like', '[QR]%'); // pedidos desde celular
            })
            ->whereNotIn('estado', ['cobrado'])
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($pedidos);
    }

    public function marcarEntregado($id)
    {
        $pedido = Pedido::findOrFail($id);

        // Permitir si es su pedido O si es pedido QR
        $esQR = str_starts_with($pedido->nota ?? '', '[QR]');
        if (!str_starts_with($pedido->nota ?? '', '[QR]') && $pedido->venta->usuario_id !== auth()->id()) {
            return response()->json(['success' => false, 'mensaje' => 'No autorizado'], 403);
        }

        $pedido->update(['estado' => 'entregado']);
        return response()->json(['success' => true, 'mensaje' => 'Pedido marcado como entregado']);
    }

    public function cobrar($id)
    {
        $pedido = Pedido::with('venta.detalleVenta.plato', 'venta.cliente', 'venta.mesa')->findOrFail($id);
        return view('admin.mozo.cobrar', compact('pedido'));
    }

    public function procesarCobro(Request $request, $id)
    {
        $request->validate([
            'tipo_comprobante' => 'required|in:factura,boleta,nota_venta',
            'pagos'            => 'required|array|min:1',
            'pagos.*.metodo'   => 'required|in:efectivo,tarjeta,yape,plin',
            'pagos.*.monto'    => 'required|numeric|min:0.01',
        ]);

        $pedido = Pedido::with('venta.cliente')->findOrFail($id);
        $venta  = $pedido->venta;

        $tipo        = $request->tipo_comprobante;
        $serie       = $tipo == 'factura' ? 'F001' : ($tipo == 'boleta' ? 'B001' : 'NV01');
        $correlativo = str_pad($venta->id, 8, '0', STR_PAD_LEFT);

        // Método principal = el de mayor monto
        $metodoPrincipal = collect($request->pagos)->sortByDesc('monto')->first()['metodo'];

        // Calcular vuelto (solo aplica en efectivo)
        $totalPagado = collect($request->pagos)->sum('monto');
        $vuelto      = max(0, round($totalPagado - $venta->total, 2));

        DB::beginTransaction();
        try {
            $venta->update([
                'estado'           => 'pagado',
                'metodo_pago'      => $metodoPrincipal,
                'tipo_comprobante' => $tipo,
                'serie'            => $serie,
                'correlativo'      => $correlativo,
                'estado_sunat'     => $tipo == 'nota_venta' ? 'no_aplica' : 'pendiente',
                'vuelto'           => $vuelto,
            ]);

            // Guardar pagos múltiples
            $venta->pagos()->delete();
            foreach ($request->pagos as $p) {
                $venta->pagos()->create([
                    'metodo' => $p['metodo'],
                    'monto'  => $p['monto'],
                ]);
            }

            $pedido->update(['estado' => 'cobrado']);
            Mesa::find($venta->mesa_id)->update(['estado' => 'libre']);

            DB::commit();
            return response()->json([
                'success' => true,
                'mensaje' => 'Cobro registrado correctamente',
                'tipo'    => $tipo,
                'vuelto'  => $vuelto,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'mensaje' => $e->getMessage()], 500);
        }
    }
}
