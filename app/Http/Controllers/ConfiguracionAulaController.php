<?php

namespace App\Http\Controllers;

use App\Models\Configuracion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ConfiguracionAulaController extends Controller
{
    public function index()
    {
        $config = Configuracion::first();
        return view('aula.configuracion.index', compact('config'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'nombre'       => 'required|string|max:150',
            'aula'         => 'required|string|max:50',
            'docente'      => 'nullable|string|max:100',
            'anio_escolar' => 'required|string|max:4',
            'turno'        => 'required|string|max:20',
            'direccion'    => 'nullable|string|max:200',
            'telefono'     => 'nullable|string|max:20',
            'email'        => 'nullable|email|max:100',
            'logo'         => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        // ← Agrega estas líneas para evitar nulos
        $data['direccion'] = $data['direccion'] ?? '';
        $data['telefono']  = $data['telefono']  ?? '';
        $data['email']     = $data['email']     ?? '';
        $data['docente']   = $data['docente']   ?? '';

        $config = Configuracion::first();
        if (!$config) {
            $config = new Configuracion();
        }

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = 'logo_colegio.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $filename);
            $data['logo'] = 'images/' . $filename;
        } else {
            unset($data['logo']);
        }

        $config->fill($data);
        $config->save();

        return redirect()->route('configuracion.aula')->with('success', 'Configuración guardada correctamente.');
    }
}
