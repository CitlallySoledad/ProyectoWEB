<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = [
        'name',
        'leader_id', // 👈 añadimos esto
    ];

    // Relación: el líder/admin del equipo
    public function leader()
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    // Relación: miembros del equipo
    public function members()
    {
        return $this->belongsToMany(User::class, 'team_user');
    }
    public function index()
{
    // Traer equipos con líder y miembros
    $teams = Team::with(['leader', 'members'])->orderByDesc('created_at')->get();

    // Arreglo para mandar a JS: miembros por equipo
    $teamMembers = [];

    foreach ($teams as $team) {
        $teamMembers[$team->id] = $team->members->map(function ($member) use ($team) {
            $role = $member->id === optional($team->leader)->id ? 'Líder' : 'Miembro';

            return [
                'name' => $member->name,
                'role' => $role,
            ];
        })->values(); // limpiar índices
    }

    return view('pagPrincipal.listaEquipo', compact('teams', 'teamMembers'));
}
}