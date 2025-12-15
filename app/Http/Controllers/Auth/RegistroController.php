<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegistroRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RegistroController extends Controller
{
    public function store(RegistroRequest $request)
    {
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
