<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    // Lista de usuarios
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->get();

        return view('admin.users.index', compact('users'));
    }

    // Formulario para crear usuario
    public function create()
    {
        return view('admin.users.create');
    }

    // Guardar nuevo usuario
    public function store(Request $request)
{
    $data = $request->validate([
        'name'     => 'required|string|max:255',
        'email'    => 'required|email|max:255|unique:users,email',
        'password' => 'required|string|min:8|confirmed',
    ]);

    User::create([
        'name'     => $data['name'],
        'email'    => $data['email'],
        'password' => Hash::make($data['password']),
        'is_admin' => true,   
    ]);

    return redirect()
        ->route('admin.users.index')
        ->with('success', 'Usuario administrador creado correctamente');
}
}
