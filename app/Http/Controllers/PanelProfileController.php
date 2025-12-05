<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class PanelProfileController extends Controller
{
    /**
     * Mostrar la vista del perfil con sus secciones dinámicas.
     */
    public function show(Request $request): View
    {
        $user = $request->user();

        // Detectar qué sección está activa
        $editDatos  = $request->query('edit') === 'datos';
        $editEquipo = $request->query('edit') === 'equipo';

        // Cargar equipos del usuario con líder y miembros
        $teams = $user->teams()
            ->with(['leader', 'members'])
            ->get();

        return view('pagPrincipal.perfil', compact(
            'user',
            'editDatos',
            'editEquipo',
            'teams'
        ));
    }

    /**
     * Actualizar los datos personales del usuario.
     */
    public function updateDatos(Request $request): RedirectResponse
    {
        $user = $request->user();

        $data = $request->validate([
            'name'             => 'required|string|max:255',
            'email'            => 'required|email',
            'curp'             => 'nullable|string|max:18',
            'fecha_nacimiento' => 'nullable|date',
            'genero'           => 'nullable|string|max:30',
            'estado_civil'     => 'nullable|string|max:50',
            'telefono'         => 'nullable|string|max:20',
            'profesion'        => 'nullable|string|max:255',
        ]);

        $user->update($data);

        return Redirect::route('panel.perfil', ['edit' => 'datos'])
            ->with('success', 'Datos personales actualizados correctamente.');
    }
}


