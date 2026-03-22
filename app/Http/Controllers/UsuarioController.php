<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Alumno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = User::with('alumno')->latest()->get();
        return view('aula.usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        $alumnos = Alumno::where('activo', true)->orderBy('apellidos')->get();
        return view('aula.usuarios.form', compact('alumnos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:100',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|string|min:6|confirmed',
            'rol'       => 'required|in:administrador,docente,tesorero,padre',
            'alumno_id' => 'nullable|exists:alumnos,id',
        ]);

        User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'rol'       => $request->rol,
            'activo'    => $request->activo == '1' ? 1 : 0,
            'alumno_id' => $request->rol === 'padre' ? $request->alumno_id : null,
        ]);

        return redirect()->route('usuarios.aula')->with('success', 'Usuario creado.');
    }
    public function edit(User $user)
    {
        $alumnos = Alumno::where('activo', true)->orderBy('apellidos')->get();
        return view('aula.usuarios.form', compact('user', 'alumnos'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'      => 'required|string|max:100',
            'email'     => 'required|email|unique:users,email,' . $user->id,
            'password'  => 'nullable|string|min:6|confirmed',
            'rol'       => 'required|in:administrador,docente,tesorero,padre',
            'alumno_id' => 'nullable|exists:alumnos,id',
        ]);

        $data = [
            'name'      => $request->name,
            'email'     => $request->email,
            'rol'       => $request->rol,
            'activo'    => $request->activo == '1' ? 1 : 0,
            'alumno_id' => $request->rol === 'padre' ? $request->alumno_id : null,
        ];

        if (!empty($request->password)) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('usuarios.aula')->with('success', 'Usuario actualizado.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('usuarios.aula')->with('error', 'No puedes eliminarte a ti mismo.');
        }
        $user->delete();
        return redirect()->route('usuarios.aula')->with('success', 'Usuario eliminado.');
    }
}
