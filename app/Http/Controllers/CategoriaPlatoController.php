<?php

namespace App\Http\Controllers;

use App\Models\CategoriaPlato;
use Illuminate\Http\Request;

class CategoriaPlatoController extends Controller
{
    public function index()
    {
        $categorias = CategoriaPlato::all();
        return view('admin.categorias_plato.index', compact('categorias'));
    }

    public function create()
    {
        return view('admin.categorias_plato.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100|unique:categorias_platos,nombre',
            'descripcion' => 'nullable|string|max:255',
        ], [
            'nombre.required' => 'El nombre de la categoría es obligatorio.',
            'nombre.unique' => 'Esta categoría ya existe.',
        ]);

        CategoriaPlato::create($request->all());

        return redirect()->route('admin.categorias_plato.index')
                         ->with('success', 'Categoría creada exitosamente.');
    }

    public function edit(CategoriaPlato $categorias_plato)
    {
        return view('admin.categorias_plato.edit', ['categoria' => $categorias_plato]);
    }

    public function update(Request $request, CategoriaPlato $categorias_plato)
    {
        $request->validate([
            'nombre' => 'required|string|max:100|unique:categorias_platos,nombre,' . $categorias_plato->id,
            'descripcion' => 'nullable|string|max:255',
        ], [
            'nombre.required' => 'El nombre de la categoría es obligatorio.',
            'nombre.unique' => 'Esta categoría ya existe.',
        ]);

        $categorias_plato->update($request->all());

        return redirect()->route('admin.categorias_plato.index')
                         ->with('success', 'Categoría actualizada exitosamente.');
    }

    public function destroy(CategoriaPlato $categorias_plato)
    {
        $categorias_plato->delete();

        return redirect()->route('admin.categorias_plato.index')
                         ->with('success', 'Categoría eliminada correctamente.');
    }
}
