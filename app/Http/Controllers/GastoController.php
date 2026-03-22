<?php

namespace App\Http\Controllers;

use App\Models\{Gasto, Actividad};
use Illuminate\Http\Request;

class GastoController extends Controller
{
    public function index(Request $request)
    {
        $actividades  = Actividad::where('activo', true)->get();
        $totalGastos  = Gasto::sum('monto');
        $porCategoria = Gasto::groupBy('categoria')
            ->selectRaw('categoria, SUM(monto) as total')
            ->pluck('total', 'categoria');

        $gastos = Gasto::with('actividad')
            ->when($request->categoria, fn($q, $c) => $q->where('categoria', $c))
            ->latest('fecha')
            ->paginate(20)
            ->withQueryString();

        return view('aula.gastos.index', compact('gastos','actividades','totalGastos','porCategoria'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'descripcion'   => 'required|string|max:200',
            'monto'         => 'required|numeric|min:0.01',
            'categoria'     => 'required|string',
            'actividad_id'  => 'nullable|exists:actividades,id',
            'fecha'         => 'required|date',
            'comprobante'   => 'nullable|string|max:50',
        ]);
        if (empty($data['actividad_id'])) $data['actividad_id'] = null;
        Gasto::create($data);
        return redirect()->route('gastos.index')->with('success', 'Gasto registrado.');
    }

    public function destroy(Gasto $gasto)
    {
        $gasto->delete();
        return redirect()->route('gastos.index')->with('success', 'Gasto eliminado.');
    }

    public function create() { return view('aula.gastos.index'); }
    public function edit(Gasto $gasto) { return view('aula.gastos.index'); }
    public function update(Request $request, Gasto $gasto) { return redirect()->route('gastos.index'); }
    public function show(Gasto $gasto) { return redirect()->route('gastos.index'); }
}
