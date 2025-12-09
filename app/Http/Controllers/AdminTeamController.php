<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;

class AdminTeamController extends Controller
{
    public function index()
    {
        $teams = Team::with(['members', 'leader'])->orderBy('name')->get();

        return view('admin.teams.index', compact('teams'));
    }

    public function create()
    {
        $users = User::orderBy('name')->get();
        return view('admin.teams.create', compact('users'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'leader_id'    => ['required', 'exists:users,id'],
            'backend_id'   => ['nullable', 'exists:users,id', 'different:leader_id'],
            'frontend_id'  => ['nullable', 'exists:users,id', 'different:leader_id', 'different:backend_id'],
            'designer_id'  => ['nullable', 'exists:users,id', 'different:leader_id', 'different:backend_id', 'different:frontend_id'],
        ]);

        $team = Team::create([
            'name'      => $data['name'],
            'leader_id' => $data['leader_id'],
        ]);

        // Construir miembros con roles (pivot team_user.role)
        $members = [];
        $members[$data['leader_id']] = ['role' => 'lider'];

        if (!empty($data['backend_id'])) {
            $members[$data['backend_id']] = ['role' => 'backend'];
        }

        if (!empty($data['frontend_id'])) {
            $members[$data['frontend_id']] = ['role' => 'frontend'];
        }

        if (!empty($data['designer_id'])) {
            $members[$data['designer_id']] = ['role' => 'disenador'];
        }

        if ($members) {
            $team->members()->sync($members);
        }

        return redirect()
            ->route('admin.teams.index')
            ->with('success', 'Equipo creado correctamente.');
    }

    public function edit(Team $team)
    {
        $team->load('members', 'leader');

        $backend   = $team->members->firstWhere('pivot.role', 'backend');
        $frontend  = $team->members->firstWhere('pivot.role', 'frontend');
        $designer  = $team->members->firstWhere('pivot.role', 'disenador');

        $users = User::orderBy('name')->get();

        return view('admin.teams.edit', [
            'team'       => $team,
            'users'      => $users,
            'backendId'  => $backend?->id,
            'frontendId' => $frontend?->id,
            'designerId' => $designer?->id,
        ]);
    }

    public function update(Request $request, Team $team)
    {
        $data = $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'leader_id'    => ['required', 'exists:users,id'],
            'backend_id'   => ['nullable', 'exists:users,id', 'different:leader_id'],
            'frontend_id'  => ['nullable', 'exists:users,id', 'different:leader_id', 'different:backend_id'],
            'designer_id'  => ['nullable', 'exists:users,id', 'different:leader_id', 'different:backend_id', 'different:frontend_id'],
        ]);

        $team->update([
            'name'      => $data['name'],
            'leader_id' => $data['leader_id'],
        ]);

        $members = [];
        $members[$data['leader_id']] = ['role' => 'lider'];

        if (!empty($data['backend_id'])) {
            $members[$data['backend_id']] = ['role' => 'backend'];
        }

        if (!empty($data['frontend_id'])) {
            $members[$data['frontend_id']] = ['role' => 'frontend'];
        }

        if (!empty($data['designer_id'])) {
            $members[$data['designer_id']] = ['role' => 'disenador'];
        }

        $team->members()->sync($members);

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

    public function join(Request $request)
    {
        $request->validate([
            'team_id' => ['required', 'exists:teams,id'],
        ]);

        $team = Team::findOrFail($request->team_id);
        $user = auth()->user();

        if ($team->members()->where('user_id', $user->id)->exists()) {
            return back()->with('info', 'Ya eres miembro de este equipo.');
        }

        $maxMembers = 4;

        if ($team->members()->count() >= $maxMembers) {
            return back()->with('error', 'Este equipo ya está lleno.');
        }

        $team->members()->attach($user->id);

        return back()->with('success', 'Te has unido al equipo correctamente.');
    }
}
