<?php
namespace App\Http\Controllers;

use App\Models\Ingrediente;
use Illuminate\Http\Request;

class IngredienteController extends Controller
{
    // Mostrar todos los ingredientes
    public function index()
    {
        $ingredientes = Ingrediente::all(); // Obtener todos los ingredientes
        return view('admin.ingredientes.index', compact('ingredientes'));
    }

    // Vista para crear un nuevo ingrediente
    public function create()
    {
        return view('admin.ingredientes.create');
    }

    // Almacenar un nuevo ingrediente
    public function store(Request $request)
    {
        // Validar los datos
        $request->validate([
            'nombre' => 'required|string|max:255|unique:ingredientes',
            'unidad' => 'required|string|max:50',
            'stock' => 'required|numeric|min:0',
            'stock_minimo' => 'required|numeric|min:0',
            'precio' => 'required|numeric|min:0',
        ]);

        // Crear el ingrediente
        Ingrediente::create($request->all());

        return redirect()->route('admin.ingredientes.index')->with('success', 'Ingrediente creado correctamente');
    }

    // Vista para editar un ingrediente
    public function edit(Ingrediente $ingrediente)
    {
        return view('admin.ingredientes.edit', compact('ingrediente'));
    }

    // Actualizar un ingrediente
    public function update(Request $request, Ingrediente $ingrediente)
    {
        // Validar los datos
        $request->validate([
            'nombre' => 'required|string|max:255|unique:ingredientes,nombre,' . $ingrediente->id,
            'unidad' => 'required|string|max:50',
            'stock' => 'required|numeric|min:0',
            'stock_minimo' => 'required|numeric|min:0',
            'precio' => 'required|numeric|min:0',
        ]);

        // Actualizar el ingrediente
        $ingrediente->update($request->all());

        return redirect()->route('admin.ingredientes.index')->with('success', 'Ingrediente actualizado correctamente');
    }

    // Eliminar un ingrediente
    public function destroy(Ingrediente $ingrediente)
    {
        $ingrediente->delete();
        return redirect()->route('admin.ingredientes.index')->with('success', 'Ingrediente eliminado correctamente');
    }
}
