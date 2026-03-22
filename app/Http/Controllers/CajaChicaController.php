<?php

namespace App\Http\Controllers;

use App\Models\CajaChica;
use App\Models\MovimientoCaja;
use Illuminate\Http\Request;

class CajaChicaController extends Controller
{
    public function index()
    {
        $cajaActiva = CajaChica::where('estado','abierta')->latest()->first();
        $historial  = CajaChica::with('movimientos')->latest()->paginate(10);
        return view('aula.caja.index', compact('cajaActiva','historial'));
    }

    // Abrir nueva caja
    public function abrir(Request $request)
    {
        $request->validate([
            'monto_inicial' => 'required|numeric|min:1',
            'descripcion'   => 'nullable|string|max:200',
        ]);

        // Cerrar caja anterior si existe
        CajaChica::where('estado','abierta')->update([
            'estado'       => 'cerrada',
            'fecha_cierre' => now(),
        ]);

        CajaChica::create([
            'monto_inicial'  => $request->monto_inicial,
            'saldo_actual'   => $request->monto_inicial,
            'estado'         => 'abierta',
            'descripcion'    => $request->descripcion,
            'user_id'        => auth()->id(),
            'fecha_apertura' => now(),
        ]);

        return redirect()->route('caja.index')->with('success', 'Caja abierta correctamente.');
    }

    // Registrar egreso
    public function egreso(Request $request)
    {
        $request->validate([
            'descripcion' => 'required|string|max:200',
            'monto'       => 'required|numeric|min:0.01',
            'categoria'   => 'required|string',
            'fecha'       => 'required|date',
            'comprobante' => 'nullable|string|max:50',
        ]);

        $caja = CajaChica::where('estado','abierta')->latest()->first();
        if (!$caja) {
            return redirect()->route('caja.index')->with('error', 'No hay caja abierta.');
        }
        if ($request->monto > $caja->saldo_actual) {
            return redirect()->route('caja.index')->with('error', 'Saldo insuficiente en caja.');
        }

        MovimientoCaja::create([
            'caja_chica_id' => $caja->id,
            'tipo'          => 'egreso',
            'descripcion'   => $request->descripcion,
            'monto'         => $request->monto,
            'categoria'     => $request->categoria,
            'comprobante'   => $request->comprobante,
            'user_id'       => auth()->id(),
            'fecha'         => $request->fecha,
        ]);

        $caja->decrement('saldo_actual', $request->monto);

        return redirect()->route('caja.index')->with('success', 'Egreso registrado.');
    }

    // Registrar reposición
    public function reponer(Request $request)
    {
        $request->validate([
            'monto'       => 'required|numeric|min:0.01',
            'descripcion' => 'nullable|string|max:200',
        ]);

        $caja = CajaChica::where('estado','abierta')->latest()->first();
        if (!$caja) {
            return redirect()->route('caja.index')->with('error', 'No hay caja abierta.');
        }

        MovimientoCaja::create([
            'caja_chica_id' => $caja->id,
            'tipo'          => 'reposicion',
            'descripcion'   => $request->descripcion ?? 'Reposición de fondos',
            'monto'         => $request->monto,
            'categoria'     => 'Reposición',
            'user_id'       => auth()->id(),
            'fecha'         => now()->toDateString(),
        ]);

        $caja->increment('saldo_actual', $request->monto);

        return redirect()->route('caja.index')->with('success', 'Reposición registrada.');
    }

    // Cerrar caja
    public function cerrar(Request $request)
    {
        $caja = CajaChica::where('estado','abierta')->latest()->first();
        if ($caja) {
            $caja->update([
                'estado'       => 'cerrada',
                'fecha_cierre' => now(),
            ]);
        }
        return redirect()->route('caja.index')->with('success', 'Caja cerrada correctamente.');
    }

    // Eliminar movimiento
    public function eliminarMovimiento(MovimientoCaja $movimiento)
    {
        $caja = $movimiento->caja;
        if ($movimiento->tipo === 'egreso') {
            $caja->increment('saldo_actual', $movimiento->monto);
        } else {
            $caja->decrement('saldo_actual', $movimiento->monto);
        }
        $movimiento->delete();
        return redirect()->route('caja.index')->with('success', 'Movimiento eliminado.');
    }

    // PDF de caja
    public function pdf(CajaChica $caja)
    {
        $config = \App\Models\Configuracion::first();
        $caja->load('movimientos');
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('aula.pdf.caja-chica', compact('caja','config'))
                ->setPaper('a4','portrait')
                ->setOptions([
                    'margin_top'=>10,'margin_bottom'=>10,
                    'margin_left'=>12,'margin_right'=>12,
                    'dpi'=>96,'isHtml5ParserEnabled'=>true,'isRemoteEnabled'=>true
                ]);
        return $pdf->download('caja-chica-'.date('Y-m-d').'.pdf');
    }
}
