<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    public function update(Request $request)
    {
        // VALIDACIÓN
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|regex:/[0-9]/|regex:/[@$!%*#?&]/',
            'new_password_confirmation' => 'required|same:new_password',
        ]);

        // USUARIO ACTUAL
        $user = auth()->user();

        // VERIFICAR CONTRASEÑA ACTUAL
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'La contraseña actual es incorrecta.');
        }

        // ACTUALIZAR CONTRASEÑA
        $user->password = Hash::make($request->new_password);
        $user->save();

        // MENSAJE DE ÉXITO
        return back()->with('success', 'Haz cambiado correctamente la contraseña. ¡Felicidades!');
    }
}
