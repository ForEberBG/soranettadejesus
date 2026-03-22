<?php

namespace App\Http\Controllers;

use App\Models\Actividad;
use Illuminate\Http\Request;

class ActividadController extends Controller
{
    public function index()
    {
        $actividades = Actividad::withSum('cobros', 'monto')->latest()->get();
        return view('aula.actividades.index', compact('actividades'));
    }

    public function create()
    {
        return view('aula.actividades.form');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'        => 'required|string|max:150',
            'cuota'         => 'required|numeric|min:0',
            'fecha_limite'  => 'nullable|date',
            'descripcion'   => 'nullable|string',
        ]);
        Actividad::create($data);
        return redirect()->route('actividades.index')->with('success', 'Actividad creada.');
    }

    public function edit(Actividad $actividad)
    {
        return view('aula.actividades.form', compact('actividad'));
    }

    public function update(Request $request, Actividad $actividad)
    {
        $data = $request->validate([
            'nombre'        => 'required|string|max:150',
            'cuota'         => 'required|numeric|min:0',
            'fecha_limite'  => 'nullable|date',
            'descripcion'   => 'nullable|string',
        ]);
        $actividad->update($data);
        return redirect()->route('actividades.index')->with('success', 'Actividad actualizada.');
    }

    public function destroy(Actividad $actividad)
    {
        $actividad->update(['activo' => false]);
        return redirect()->route('actividades.index')->with('success', 'Actividad desactivada.');
    }
}
