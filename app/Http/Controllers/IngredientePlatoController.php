<?php
namespace App\Http\Controllers;

use App\Models\IngredientePlato;
use App\Models\Plato;
use App\Models\Ingrediente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IngredientePlatoController extends Controller
{
    // Mostrar todos los ingredientes de los platos
    public function index()
    {
        $ingredientesPlatos = IngredientePlato::with(['plato', 'ingrediente'])->get();
        return view('admin.ingredientes_platos.index', compact('ingredientesPlatos'));
    }

    // Vista de creación de ingredientes en platos
    public function create()
    {
        $platos = Plato::all();
        $ingredientes = Ingrediente::all();
        return view('admin.ingredientes_platos.create', compact('platos', 'ingredientes'));
    }

    // Almacenar la relación entre ingrediente y plato
    public function store(Request $request)
{
    // Validación para ingredientes y cantidades
    $request->validate([
        'plato_id' => 'required|exists:platos,id',
        'ingrediente_id' => 'required|array',
        'ingrediente_id.*' => 'exists:ingredientes,id',
        'cantidad_usada' => 'required|array',
        'cantidad_usada.*' => 'numeric|min:1',
    ]);

    // Iniciar transacción para asegurar la integridad de los datos
    DB::beginTransaction();

    try {
        // Recorrer los ingredientes y cantidades, registrando los datos
        foreach ($request->ingrediente_id as $index => $ingredienteId) {
            IngredientePlato::create([
                'plato_id' => $request->plato_id,
                'ingrediente_id' => $ingredienteId,
                'cantidad_usada' => $request->cantidad_usada[$index],
            ]);
        }

        // Confirmar transacción
        DB::commit();
        return redirect()->route('admin.ingredientes_platos.index')->with('success', 'Ingrediente asignado correctamente');
    } catch (\Exception $e) {
        // Revertir cambios en caso de error
        DB::rollBack();
        return redirect()->back()->with('error', 'Error al asignar ingredientes: ' . $e->getMessage());
    }
}


    // Vista de edición
    public function edit(IngredientePlato $ingredientePlato)
    {
        $platos = Plato::all();
        $ingredientes = Ingrediente::all();
        return view('admin.ingredientes_platos.edit', compact('ingredientes', 'platos', 'ingredientePlato'));
    }

    // Actualizar relación
    public function update(Request $request, IngredientePlato $ingredientePlato)
    {
        $request->validate([
            'plato_id' => 'required|exists:platos,id',
            'ingrediente_id' => 'required|exists:ingredientes,id',
            'cantidad_usada' => 'required|numeric|min:0',
        ]);

        $ingredientePlato->update($request->all());

        return redirect()->route('admin.ingredientes_platos.index')->with('success', 'Ingrediente actualizado correctamente');
    }

    // Eliminar relación
    public function destroy(IngredientePlato $ingredientePlato)
    {
        $ingredientePlato->delete();
        return redirect()->route('admin.ingredientes_platos.index')->with('success', 'Ingrediente eliminado correctamente');
    }
}
