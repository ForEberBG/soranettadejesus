<?php

namespace App\Http\Controllers;

use App\Models\Caja;
use App\Models\Venta;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class CajaController extends Controller
{
    public function index()
    {
        $cajaAbierta = Caja::where('estado', 'abierta')->latest()->first();
        $historial   = Caja::with('usuario')->where('estado', 'cerrada')
            ->orderByDesc('id')->take(10)->get();
        return view('admin.caja.index', compact('cajaAbierta', 'historial'));
    }

    public function abrir(Request $request)
    {
        $request->validate([
            'monto_inicial' => 'required|numeric|min:0',
            'observaciones' => 'nullable|string',
        ]);

        // Solo una caja abierta a la vez
        if (Caja::where('estado', 'abierta')->exists()) {
            return back()->with('error', 'Ya hay una caja abierta.');
        }

        Caja::create([
            'usuario_id'    => auth()->id(),
            'monto_inicial' => $request->monto_inicial,
            'observaciones' => $request->observaciones,
            'apertura_at'   => now(),
            'estado'        => 'abierta',
        ]);

        return back()->with('success', 'Caja abierta correctamente.');
    }

    public function cerrar(Caja $caja)
    {
        $ventas = Venta::with('pagos')
            ->where('estado', 'pagado')
            ->whereDate('created_at', $caja->apertura_at->toDateString())
            ->get();

        // Sumar desde tabla pagos (pagos mixtos)
        $totalEfectivo = 0;
        $totalYape = 0;
        $totalPlin = 0;
        $totalTarjeta = 0;

        foreach ($ventas as $v) {
            if ($v->pagos->count() > 0) {
                $totalEfectivo += $v->pagos->where('metodo', 'efectivo')->sum('monto');
                $totalYape     += $v->pagos->where('metodo', 'yape')->sum('monto');
                $totalPlin     += $v->pagos->where('metodo', 'plin')->sum('monto');
                $totalTarjeta  += $v->pagos->where('metodo', 'tarjeta')->sum('monto');
            } else {
                // Ventas antiguas sin tabla pagos
                if ($v->metodo_pago == 'efectivo') $totalEfectivo += $v->total;
                if ($v->metodo_pago == 'yape')     $totalYape     += $v->total;
                if ($v->metodo_pago == 'plin')     $totalPlin     += $v->total;
                if ($v->metodo_pago == 'tarjeta')  $totalTarjeta  += $v->total;
            }
        }

        $caja->update([
            'total_efectivo' => $totalEfectivo,
            'total_yape'     => $totalYape,
            'total_plin'     => $totalPlin,
            'total_tarjeta'  => $totalTarjeta,
            'total_ventas'   => $ventas->sum('total'),
            'num_ventas'     => $ventas->count(),
            'cierre_at'      => now(),
            'estado'         => 'cerrada',
        ]);

        return back()->with('success', 'Caja cerrada correctamente.');
    }

    public function reportePdf(Caja $caja)
    {
        $caja->load('usuario');
        $ventas = Venta::with('cliente', 'detalleVenta.plato')
            ->where('estado', 'pagado')
            ->whereDate('created_at', $caja->apertura_at->toDateString())
            ->when($caja->cierre_at, fn($q) => $q->where('created_at', '<=', $caja->cierre_at))
            ->orderBy('id')
            ->get();

        $pdf = Pdf::loadView('admin.caja.reporte_pdf', compact('caja', 'ventas'))
            ->setPaper('A4');

        return $pdf->download("Reporte_Caja_{$caja->id}.pdf");
    }

    public function cobrosMozos()
    {
        $cajaAbierta = \App\Models\Caja::where('estado', 'abierta')->latest()->first();
        if (!$cajaAbierta) return response()->json(['cobros' => []]);

        $cobros = \App\Models\Venta::with(['cliente', 'mesa', 'usuario'])
            ->where('estado', 'pagado')
            ->where('created_at', '>=', $cajaAbierta->apertura_at)
            ->orderByDesc('created_at')
            ->get()
            ->map(fn($v) => [
                'total'      => $v->total,
                'metodo_pago' => $v->metodo_pago,
                'created_at' => $v->created_at,
                'mesa'       => $v->mesa->numero ?? '-',
                'mozo'       => $v->usuario->name ?? 'Sistema',
                'vuelto'     => $v->vuelto ?? 0,  // ← NUEVO
            ]);

        return response()->json(['cobros' => $cobros]);
    }
}
