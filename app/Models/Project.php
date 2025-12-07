<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = ['name', 'team_id', 'event_id', 'status'];

    // Relación con el equipo
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    // Relación con el evento
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    // Relación con las evaluaciones
    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }
}

