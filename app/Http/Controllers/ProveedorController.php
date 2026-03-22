<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    // Mostrar todos los proveedores
    public function index()
    {
        $proveedores = Proveedor::all();
        return view('admin.proveedores.index', compact('proveedores'));
    }

    // Mostrar el formulario para crear un nuevo proveedor
    public function create()
    {
        return view('admin.proveedores.create');
    }

    // Almacenar el nuevo proveedor
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|unique:proveedores,nombre',
            'telefono' => 'nullable|string',
            'direccion' => 'nullable|string',
        ]);

        Proveedor::create($request->all());

        return redirect()->route('admin.proveedores.index')->with('success', 'Proveedor registrado correctamente.');
    }

    // Mostrar el formulario para editar un proveedor
    public function edit(Proveedor $proveedor)
    {
        return view('admin.proveedores.edit', compact('proveedor'));
    }

    // Actualizar un proveedor
    public function update(Request $request, Proveedor $proveedor)
    {
        $request->validate([
            'nombre' => 'required|unique:proveedores,nombre,' . $proveedor->id,
            'telefono' => 'nullable|string',
            'direccion' => 'nullable|string',
        ]);
        $data = $request->all();

        $proveedor->update($data);

        return redirect()->route('admin.proveedores.index')->with('success', 'Proveedor actualizado correctamente.');
    }

    // Eliminar un proveedor
    public function destroy(Proveedor $proveedor)
    {
        $proveedor->delete();
        return redirect()->route('admin.proveedores.index')->with('success', 'Proveedor eliminado correctamente.');
    }
}
