<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParticipantTeamController extends Controller
{
    // Muestra la lista de equipos en el panel del participante
    public function index()
    {
        // Obtener equipos ordenados
        $teams = Team::orderBy('created_at', 'desc')->get();
        return view('pagPrincipal.listaEquipo', compact('teams'));
    }

    // Muestra el formulario de "Crear Equipo" (Diseño Participante)
    public function create()
    {
        return view('pagPrincipal.crearEquipo'); 
    }

    // Guarda el equipo creado por el participante
    public function store(Request $request)
    {
        // 1. Validación específica para el formulario del participante
        $request->validate([
            'team_name' => 'required|string|max:255',
            // 'user_name' es readonly, no es crítico validarlo, usaremos Auth
        ]);

        // 2. Crear el equipo
        Team::create([
            'name'       => $request->input('team_name'),
            'user_name'  => Auth::check() ? Auth::user()->name : 'Usuario', // Usa el Auth real
            'role'       => 'Lider',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 3. Redirigir al panel de lista
        return redirect()->route('panel.lista-equipo')
            ->with('success', 'Equipo creado con éxito');
    }

    // Lógica para unirse a un equipo
    public function join(Request $request)
    {
        $request->validate([
            'team_id' => 'required|exists:teams,id',
        ]);

        // AQUÍ IRÍA LA LÓGICA DE UNIRSE A LA BD (tabla pivote)
        // Ejemplo: Auth::user()->teams()->attach($request->team_id);
        
        return redirect()->route('panel.lista-equipo')
            ->with('success', '¡Te has unido al equipo correctamente!');
    }
}