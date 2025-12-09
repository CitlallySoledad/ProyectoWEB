<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RegistroController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'control'    => 'required',
            'nombre'     => 'required',
            'ap_paterno' => 'required',
            'ap_materno' => 'required',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|confirmed|min:6',
            'telefono'   => 'nullable',
            'carrera'    => 'nullable',
            'role'       => 'required|in:student,judge',
        ]);

        // Crear rol si no existe (evita error cuando faltan seeds)
        $role = Role::firstOrCreate(['name' => $request->role], ['guard_name' => 'web']);

        $user = User::create([
            'name'     => $request->nombre.' '.$request->ap_paterno,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'telefono' => $request->telefono,
        ]);

        // Asignar rol seleccionado
        $user->assignRole($role);

        Auth::login($user);

        // Redirigir segun rol
        if ($user->hasRole('judge')) {
            return redirect()->route('judge.projects.index');
        }

        return redirect()->route('panel.participante');
    }
}
