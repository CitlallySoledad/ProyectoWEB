<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = [
        'name',
        'leader_id',
        'members',    // líder del equipo
    ];

    // Relación: líder/admin del equipo
    public function leader()
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    // Relación: miembros del equipo (usuarios)
    public function members()
    {
        return $this->belongsToMany(User::class, 'team_user');
    }

    protected $casts = [
        'members' => 'array', // 👈 para usarlo como arreglo en Blade
    ];
}
