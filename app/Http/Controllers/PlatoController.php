<?php

namespace App\Http\Controllers;

use App\Models\Plato;
use App\Models\CategoriaPlato;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PlatoController extends Controller
{
    public function index()
    {
        $platos = Plato::with('categoria')->get();
        return view('admin.platos.index', compact('platos'));
    }

    public function create()
    {
        $categorias = CategoriaPlato::all();
        return view('admin.platos.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|unique:platos,nombre',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'categoria_id' => 'required|exists:categorias_platos,id',
            'estado' => 'required|in:disponible,no disponible',
            'imagen' => 'nullable|image|max:2048',
        ]);
        
        $data = $request->all();

        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('platos', 'public');
        }
        
        Plato::create($data);
        return redirect()->route('admin.platos.index')->with('success', 'Plato registrado correctamente.');
    }

    public function edit(Plato $plato)
    {
        $categorias = CategoriaPlato::all();
        return view('admin.platos.edit', compact('plato', 'categorias'));
    }

    public function update(Request $request, Plato $plato)
    {
        $request->validate([
            'nombre' => 'required|unique:platos,nombre,' . $plato->id,
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'categoria_id' => 'required|exists:categorias_platos,id',
            'estado' => 'required|in:disponible,no disponible',
            'imagen' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('imagen')) {
            if ($plato->imagen && Storage::disk('public')->exists($plato->imagen)) {
                Storage::disk('public')->delete($plato->imagen);
            }
            $data['imagen'] = $request->file('imagen')->store('platos', 'public');
        }

        $plato->update($data);

        return redirect()->route('admin.platos.index')->with('success', 'Plato actualizado correctamente.');
    }

    public function destroy(Plato $plato)
    {
        if ($plato->imagen && Storage::disk('public')->exists($plato->imagen)) {
            Storage::disk('public')->delete($plato->imagen);
        }

        $plato->delete();

        return redirect()->route('admin.platos.index')->with('success', 'Plato eliminado correctamente.');
    }
}
