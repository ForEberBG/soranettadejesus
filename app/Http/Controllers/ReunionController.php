<?php

namespace App\Http\Controllers;

use App\Models\{Reunion, Alumno, Asistencia};
use Illuminate\Http\Request;

class ReunionController extends Controller
{
    public function index()
    {
        $reuniones    = Reunion::with('asistencias')->latest('fecha')->get();
        $alumnos      = Alumno::where('activo', true)->orderBy('apellidos')->get();
        $totalAlumnos = $alumnos->count();
        return view('aula.reuniones.index', compact('reuniones','alumnos','totalAlumnos'));
    }

    public function create()
    {
        return view('aula.reuniones.form');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tema'        => 'required|string|max:200',
            'fecha'       => 'required|date',
            'hora'        => 'required',
            'lugar'       => 'nullable|string|max:100',
            'notas'       => 'nullable|string',
            'imagen_acta' => 'nullable|image|mimes:png,jpg,jpeg|max:5120',
        ]);

        if ($request->hasFile('imagen_acta')) {
            if (!file_exists(public_path('images/actas'))) {
                mkdir(public_path('images/actas'), 0755, true);
            }
            $file     = $request->file('imagen_acta');
            $filename = 'acta_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/actas'), $filename);
            $data['imagen_acta'] = 'images/actas/' . $filename;
        }

        Reunion::create($data);
        return redirect()->route('reuniones.index')->with('success', 'Reunión registrada.');
    }

    public function edit(Reunion $reunion)
    {
        return view('aula.reuniones.form', compact('reunion'));
    }

    public function update(Request $request, Reunion $reunion)
    {
        $data = $request->validate([
            'tema'        => 'required|string|max:200',
            'fecha'       => 'required|date',
            'hora'        => 'required',
            'lugar'       => 'nullable|string|max:100',
            'notas'       => 'nullable|string',
            'imagen_acta' => 'nullable|image|mimes:png,jpg,jpeg|max:5120',
        ]);

        if ($request->hasFile('imagen_acta')) {
            // Eliminar imagen anterior
            if ($reunion->imagen_acta && file_exists(public_path($reunion->imagen_acta))) {
                unlink(public_path($reunion->imagen_acta));
            }
            if (!file_exists(public_path('images/actas'))) {
                mkdir(public_path('images/actas'), 0755, true);
            }
            $file     = $request->file('imagen_acta');
            $filename = 'acta_' . $reunion->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/actas'), $filename);
            $data['imagen_acta'] = 'images/actas/' . $filename;
        } else {
            unset($data['imagen_acta']);
        }

        $reunion->update($data);
        return redirect()->route('reuniones.index')->with('success', 'Reunión actualizada.');
    }

    public function destroy(Reunion $reunion)
    {
        if ($reunion->imagen_acta && file_exists(public_path($reunion->imagen_acta))) {
            unlink(public_path($reunion->imagen_acta));
        }
        $reunion->delete();
        return redirect()->route('reuniones.index')->with('success', 'Reunión eliminada.');
    }

    public function asistencia(Request $request, Reunion $reunion)
    {
        $request->validate([
            'alumno_id' => 'required|exists:alumnos,id',
            'asistio'   => 'required|boolean',
        ]);
        Asistencia::updateOrCreate(
            ['reunion_id' => $reunion->id, 'alumno_id' => $request->alumno_id],
            ['asistio'    => $request->asistio]
        );
        return back()->with('success', 'Asistencia actualizada.');
    }

    public function show(Reunion $reunion)
    {
        return redirect()->route('reuniones.index');
    }
}
