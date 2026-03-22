<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use Illuminate\Http\Request;

class AlumnoController extends Controller
{
    public function index(Request $request)
    {
        $alumnos = Alumno::where('activo', true)
            ->with('cobros')
            ->when($request->buscar, fn($q, $b) =>
                $q->where('apellidos', 'like', "%$b%")
                  ->orWhere('nombres', 'like', "%$b%")
            )
            ->orderBy('apellidos')
            ->paginate(20)
            ->withQueryString();

        return view('aula.alumnos.index', compact('alumnos'));
    }

    public function create()
    {
        return view('aula.alumnos.form');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'apellidos'     => 'required|string|max:100',
            'nombres'       => 'required|string|max:100',
            'dni'           => 'nullable|string|max:8',
            'seccion'       => 'nullable|string|max:2',
            'apoderado'     => 'required|string|max:100',
            'celular'       => 'nullable|string|max:20',
            'parentesco'    => 'nullable|string|max:30',
            'observaciones' => 'nullable|string',
            'foto'          => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        // Subir foto
        if ($request->hasFile('foto')) {
            $file     = $request->file('foto');
            $filename = 'alumno_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/alumnos'), $filename);
            $data['foto'] = 'images/alumnos/' . $filename;
        }

        $data['activo'] = true;
        Alumno::create($data);

        return redirect()->route('alumnos.index')->with('success', 'Alumno registrado correctamente.');
    }

    public function show(Alumno $alumno)
    {
        $cobros = $alumno->cobros()->with('actividad')->latest('fecha')->get();
        return view('aula.alumnos.show', compact('alumno', 'cobros'));
    }

    public function edit(Alumno $alumno)
    {
        return view('aula.alumnos.form', compact('alumno'));
    }

    public function update(Request $request, Alumno $alumno)
    {
        $data = $request->validate([
            'apellidos'     => 'required|string|max:100',
            'nombres'       => 'required|string|max:100',
            'dni'           => 'nullable|string|max:8',
            'seccion'       => 'nullable|string|max:2',
            'apoderado'     => 'required|string|max:100',
            'celular'       => 'nullable|string|max:20',
            'parentesco'    => 'nullable|string|max:30',
            'observaciones' => 'nullable|string',
            'foto'          => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        // Subir foto nueva
        if ($request->hasFile('foto')) {
            // Eliminar foto anterior
            if ($alumno->foto && file_exists(public_path($alumno->foto))) {
                unlink(public_path($alumno->foto));
            }
            $file     = $request->file('foto');
            $filename = 'alumno_' . $alumno->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/alumnos'), $filename);
            $data['foto'] = 'images/alumnos/' . $filename;
        } else {
            unset($data['foto']);
        }

        $alumno->update($data);
        return redirect()->route('alumnos.index')->with('success', 'Alumno actualizado correctamente.');
    }

    public function destroy(Alumno $alumno)
    {
        $alumno->update(['activo' => false]);
        return redirect()->route('alumnos.index')->with('success', 'Alumno removido.');
    }
}
