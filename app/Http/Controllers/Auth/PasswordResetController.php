<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordResetMail;

class PasswordResetController extends Controller
{
    /**
     * Muestra el formulario para solicitar el enlace de restablecimiento
     */
    public function showLinkRequestForm()
    {
        return view('pagPrincipal.forgot-password');
    }

    /**
     * Envía el enlace de restablecimiento por correo
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ], [
            'email.exists' => 'No encontramos ninguna cuenta con ese correo electrónico.',
        ]);

        // Generar token único
        $token = Str::random(60);

        // Guardar en la tabla password_reset_tokens
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'email' => $request->email,
                'token' => Hash::make($token),
                'created_at' => now(),
            ]
        );

        // Obtener el usuario
        $user = User::where('email', $request->email)->first();

        // Enviar correo
        Mail::to($request->email)->send(new PasswordResetMail($user, $token));

        return back()->with('status', '¡Hemos enviado el enlace de recuperación a tu correo electrónico!');
    }

    /**
     * Muestra el formulario para restablecer la contraseña
     */
    public function showResetForm($token)
    {
        return view('pagPrincipal.reset-password', ['token' => $token]);
    }

    /**
     * Restablece la contraseña
     */
    public function reset(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email', 'exists:users,email'],
            'password' => ['required', 'min:8', 'confirmed', 'regex:/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]+$/'],
        ], [
            'email.exists' => 'No encontramos ninguna cuenta con ese correo electrónico.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.regex' => 'La contraseña debe contener al menos una letra y un número.',
        ]);

        // Buscar el token en la base de datos
        $passwordReset = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$passwordReset || !Hash::check($request->token, $passwordReset->token)) {
            return back()->withErrors(['email' => 'El enlace de recuperación no es válido o ha expirado.']);
        }

        // Verificar que el token no sea muy antiguo (60 minutos)
        if (now()->diffInMinutes($passwordReset->created_at) > 60) {
            return back()->withErrors(['email' => 'El enlace de recuperación ha expirado. Solicita uno nuevo.']);
        }

        // Actualizar la contraseña
        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        // Eliminar el token usado
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('status', '¡Tu contraseña ha sido restablecida exitosamente! Inicia sesión con tu nueva contraseña.');
    }
}
