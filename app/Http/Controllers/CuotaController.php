<?php

namespace App\Http\Controllers;

use App\Models\{Alumno, Actividad, Cobro};
use Illuminate\Http\Request;

class CuotaController extends Controller
{
    public function index(Request $request)
    {
        $alumnos      = Alumno::where('activo', true)->orderBy('apellidos')->get();
        $actividades  = Actividad::where('activo', true)->withSum('cobros', 'monto')->get();
        $totalAlumnos = $alumnos->count();

        $cobros = Cobro::with('alumno', 'actividad')
            ->when($request->actividad_id, fn($q, $id) => $q->where('actividad_id', $id))
            ->latest('fecha')
            ->paginate(20)
            ->withQueryString();

        return view('aula.cuotas.index', compact('alumnos','actividades','cobros','totalAlumnos'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'alumno_id'    => 'required|exists:alumnos,id',
            'actividad_id' => 'required|exists:actividades,id',
            'monto'        => 'required|numeric|min:0.01',
            'fecha'        => 'required|date',
            'metodo_pago'  => 'required|in:efectivo,yape,plin,otro',
            'captura'      => 'nullable|image|mimes:png,jpg,jpeg|max:5120',
            'observaciones'=> 'nullable|string',
        ]);

        // Subir captura si existe
        if ($request->hasFile('captura')) {
            $file     = $request->file('captura');
            $filename = 'captura_' . time() . '.' . $file->getClientOriginalExtension();

            // Crear carpeta si no existe
            if (!file_exists(public_path('images/capturas'))) {
                mkdir(public_path('images/capturas'), 0755, true);
            }

            $file->move(public_path('images/capturas'), $filename);
            $data['captura'] = 'images/capturas/' . $filename;
        }

        Cobro::create($data);
        return redirect()->route('cuotas.index')->with('success', 'Cobro registrado correctamente.');
    }

    public function destroy(Cobro $cobro)
    {
        // Eliminar captura si existe
        if ($cobro->captura && file_exists(public_path($cobro->captura))) {
            unlink(public_path($cobro->captura));
        }
        $cobro->delete();
        return redirect()->route('cuotas.index')->with('success', 'Cobro anulado.');
    }
}
