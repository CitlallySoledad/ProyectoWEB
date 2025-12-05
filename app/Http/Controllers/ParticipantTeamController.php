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
        $teams = Team::orderBy('created_at', 'desc')->with('members')  ->get();
        return view('pagPrincipal.listaEquipo', compact('teams'));
            $user = auth()->user();

    // Si NO tiene equipo → mostrar advertencia
    if (!$user->team_id) {
        return view('pagPrincipal.miEquipo', [
            'team' => null,
            'members' => [],
            'leader' => null
        ]);
    }

    // Obtener el equipo del usuario
    $team = Team::with('members', 'leader')->find($user->team_id);

    return view('pagPrincipal.miEquipo', [
        'team' => $team,
        'members' => $team->members,
        'leader' => $team->leader,
    ]);
    }

        public function miEquipo()
    {
        $user = auth()->user();
            // Equipo principal (si usas team_id en users)
    $mainTeam = null;
    if ($user->team_id) {
        $mainTeam = Team::with(['members', 'leader'])->find($user->team_id);
    }

    // TODOS los equipos donde participa (por la relación members)
    $userTeams = Team::with('leader')
        ->whereHas('members', function ($q) use ($user) {
            $q->where('users.id', $user->id);
        })
        ->get();

    return view('pagPrincipal.miEquipo', [
        'team'      => $mainTeam,   // equipo principal
        'userTeams' => $userTeams,  // todos los equipos donde está
    ]);

        if (!$user->team_id) {
            return view('pagPrincipal.miEquipo', [
                'team'    => null,
                'members' => [],
                'leader'  => null,
            ]);
        }

        $team = Team::with(['members', 'leader'])->find($user->team_id);

        return view('pagPrincipal.miEquipo', [
            'team'    => $team,
            'members' => $team->members,
            'leader'  => $team->leader,
        ]);
    }
    // Muestra el formulario de "Crear Equipo" (Diseño Participante)
    public function create()
    {
        return view('pagPrincipal.crearEquipo'); 
    }

    // Guarda el equipo creado por el participante
   public function store(Request $request)
{
    $data = $request->validate([
        'team_name' => ['required', 'string', 'max:255'],
    ]);

    // Usuario autenticado
    $user = auth()->user();

    // 1️⃣ Crear el equipo
    $team = Team::create([
        'name' => $data['team_name'],
        'leader_id' => $user->id,  
    ]);

    // 2️⃣ Agregar automáticamente al creador a la tabla pivote
    $team->members()->attach($user->id);

    // 3️⃣ Guardar nombre del creador dentro del modelo (si la tabla no tiene leader_id)
    // Esto evita migraciones y te sirve para mostrar "líder" en las vistas:


    return redirect()
        ->route('panel.lista-equipo')
        ->with('success', 'Equipo creado y te has unido automáticamente.');
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