<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;


class PanelPasswordController extends Controller
{
    /**
     * Mostrar el formulario de cambiar contraseña
     */
    public function show()
    {
        return view('pagPrincipal.cambiarContrasena');
    }

    /**
     * Actualizar la contraseña del usuario autenticado
     */
    public function update(Request $request)
    {
        // 1. Validar datos
        $request->validate(
            [
                'current_password' => ['required'],
                'new_password'     => [
                    'required',
                    'confirmed',  // compara con new_password_confirmation
                    Password::min(8)
                        ->mixedCase()
                        ->numbers()
                        ->symbols(),
                ],
            ],
            [
                'current_password.required' => 'Debes escribir tu contraseña actual.',
                'new_password.required'     => 'Debes escribir la nueva contraseña.',
                'new_password.confirmed'    => 'La confirmación de la nueva contraseña no coincide.',
            ]
        );

        $user = $request->user();

        // 2. Verificar que la contraseña actual sea correcta
        if (! Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'La contraseña actual no es correcta.',
            ]);
        }

        // 3. Guardar nueva contraseña
        $user->password = Hash::make($request->new_password);
        $user->save();

        // 4. Regresar con mensaje de éxito
        return back()->with('success', 'Tu contraseña se actualizó correctamente.');
    }
}
