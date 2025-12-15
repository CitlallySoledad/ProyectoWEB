<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    // Lista de usuarios
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    // Formulario para crear usuario
    public function create()
    {
        return view('admin.users.create');
    }

    // Guardar nuevo usuario
    public function store(StoreUserRequest $request)
{
    $data = $request->validated();

    $user = User::create([
        'name'     => $data['name'],
        'email'    => $data['email'],
        'password' => Hash::make($data['password']),
        'is_admin' => true,   
    ]);

    // Asegurar rol admin (spatie)
    $adminRole = Role::firstOrCreate(['name' => 'admin'], ['guard_name' => 'web']);
    $user->assignRole($adminRole);

    return redirect()
        ->route('admin.users.index')
        ->with('success', 'Usuario administrador creado correctamente');
}

    // Eliminar usuario
    public function destroy(User $user)
    {
        // Prevenir que el usuario se elimine a sí mismo
        if ($user->id === auth()->id()) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'No puedes eliminar tu propio usuario.');
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Usuario eliminado correctamente.');
    }
}
