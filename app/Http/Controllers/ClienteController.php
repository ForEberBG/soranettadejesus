<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = Cliente::all();
        return view('admin.clientes.index', compact('clientes'));
    }

    public function create()
    {
        return view('admin.clientes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'documento' => 'nullable|string|max:20|unique:clientes,documento',
        ]);

        Cliente::create($request->all());

        return redirect()->route('admin.clientes.index')->with('success', 'Cliente registrado correctamente.');
    }

    public function edit(Cliente $cliente)
    {
        return view('admin.clientes.edit', compact('cliente'));
    }

    public function update(Request $request, Cliente $cliente)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'documento' => 'nullable|string|max:20|unique:clientes,documento,' . $cliente->id,
        ]);

        $cliente->update($request->all());

        return redirect()->route('admin.clientes.index')->with('success', 'Cliente actualizado correctamente.');
    }

    public function destroy(Cliente $cliente)
    {
        $cliente->delete();

        return redirect()->route('admin.clientes.index')->with('success', 'Cliente eliminado correctamente.');
    }
}
