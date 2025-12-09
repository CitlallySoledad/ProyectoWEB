<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = [
        'name',
        'leader_id',
    ];

    // Relación: líder/admin del equipo
    public function leader()
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    // Relación: miembros del equipo (usuarios)
    public function members()
    {
        return $this->belongsToMany(User::class, 'team_user')
            ->withPivot('role')
            ->withTimestamps();
    }

    // Relación: solicitudes de unirse al equipo
    public function joinRequests()
    {
        return $this->hasMany(TeamJoinRequest::class);
    }

    // Relación: invitaciones enviadas por el líder
    public function invitations()
    {
        return $this->hasMany(TeamInvitation::class);
    }

    // Relación: eventos en los que está inscrito el equipo
    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_team')
            ->withTimestamps();
    }

    // Relación: proyectos del equipo
    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    // No castear ni exponer 'members' como atributo: usamos la relación belongsToMany
}
