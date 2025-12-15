<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;   // 👈 IMPORTANTE

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;  // 👈 IMPORTANTE

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',         // lo puedes seguir usando o dejar de usar luego

        // campos extra de perfil
        'curp',
        'fecha_nacimiento',
        'genero',
        'estado_civil',
        'telefono',
        'profesion',
        'profile_photo',    // foto de perfil
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_admin'          => 'boolean',
        'fecha_nacimiento'  => 'date',
    ];

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'team_user')
            ->withPivot('role')
            ->withTimestamps();
    }

    // Proyectos asignados a este usuario como juez
    public function assignedProjects()
    {
        return $this->belongsToMany(Project::class, 'project_judge');
    }

    // Alias para compatibilidad
    public function projects()
    {
        return $this->assignedProjects();
    }
}


