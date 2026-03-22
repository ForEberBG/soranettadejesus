<?php

namespace App\Http\Controllers;

use App\Models\Mesa;
use Illuminate\Http\Request;

class MesaController extends Controller
{
    public function index()
    {
        $mesas = Mesa::all();
        return view('admin.mesas.index', compact('mesas'));
    }

    public function create()
    {
        return view('admin.mesas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'numero' => 'required|unique:mesas,numero',
            'estado' => 'required',
            'capacidad' => 'nullable|integer|min:1',
        ], [
            'numero.unique' => 'Este número de mesa ya está registrado.',
            'numero.required' => 'El número de mesa es obligatorio.',
        ]);

        Mesa::create($request->all());

        return redirect()->route('admin.mesas.index')->with('success', 'Mesa creada correctamente.');
    }

    public function edit(Mesa $mesa)
    {
        return view('admin.mesas.edit', compact('mesa'));
    }

    public function update(Request $request, Mesa $mesa)
    {
        $request->validate([
            'numero' => 'required|unique:mesas,numero,' . $mesa->id,
            'estado' => 'required',
            'capacidad' => 'nullable|integer|min:1',
        ]);

        $mesa->update($request->all());

        return redirect()->route('admin.mesas.index')->with('success', 'Mesa actualizada correctamente.');
    }

    public function destroy(Mesa $mesa)
    {
        $mesa->delete();
        return redirect()->route('admin.mesas.index')->with('success', 'Mesa eliminada.');
    }


    public function qrCodes()
    {
        $mesas = Mesa::orderBy('numero')->get();
        return view('admin.mesas.qr', compact('mesas'));
    }
}
