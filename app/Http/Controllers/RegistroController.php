<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegistroController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validar datos del formulario
        $request->validate([
            'control'    => 'required',
            'nombre'     => 'required',
            'ap_paterno' => 'required',
            'ap_materno' => 'required',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|confirmed|min:6',
            'telefono'   => 'nullable',
            'carrera'    => 'nullable',
        ]);

        // 2. Crear usuario en la tabla users
        //    (de momento solo guardamos name, email y password)
        $user = User::create([
            'name'     => $request->nombre.' '.$request->ap_paterno,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // 3. Iniciar sesión automáticamente con ese usuario
        Auth::login($user);

        // 4. Redirigir al panel del participante
        return redirect()->route('panel.participante');
    }
}
