<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;

class AdminTeamController extends Controller
{
    public function index()
    {
        $teams = Team::orderBy('name')->get();

        return view('admin.teams.index', compact('teams'));
    }

    public function create()
    {
        return view('admin.teams.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        Team::create($data);

        return redirect()
            ->route('admin.teams.index')
            ->with('success', 'Equipo creado correctamente.');
    }

    public function edit(Team $team)
    {
        return view('admin.teams.edit', compact('team'));
    }

    public function update(Request $request, Team $team)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $team->update($data);

        return redirect()
            ->route('admin.teams.index')
            ->with('success', 'Equipo actualizado correctamente.');
    }

    public function destroy(Team $team)
    {
        $team->delete();

        return redirect()
            ->route('admin.teams.index')
            ->with('success', 'Equipo eliminado.');
    }

    // 👇👇👋 AQUÍ EMPIEZA LO NUEVO 👋👇👇
    public function join(Request $request)
    {
        // Validamos que venga el ID del equipo y exista
        $request->validate([
            'team_id' => ['required', 'exists:teams,id'],
        ]);

        $team = Team::findOrFail($request->team_id);
        $user = auth()->user();

        // 1. Revisar si YA es miembro
        if ($team->members()->where('user_id', $user->id)->exists()) {
            return back()->with('info', 'Ya eres miembro de este equipo.');
        }

        // 2. Revisar si el equipo está lleno (máximo 4, ajusta si quieres)
        $maxMembers = 4;

        if ($team->members()->count() >= $maxMembers) {
            return back()->with('error', 'Este equipo ya está lleno.');
        }

        // 3. Agregar al usuario al equipo
        $team->members()->attach($user->id);

        return back()->with('success', 'Te has unido al equipo correctamente.');
    }
}
