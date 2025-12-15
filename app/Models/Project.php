<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'team_id', 'event_id', 'status', 'rubric_id'];

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

    // Relación con los jueces asignados (pivot)
    public function judges()
    {
        return $this->belongsToMany(User::class, 'project_judge');
    }

    // Relación con la rúbrica
    public function rubric()
    {
        return $this->belongsTo(Rubric::class);
    }

    // Relación con los documentos (PDFs)
    public function documents()
    {
        return $this->hasMany(ProjectDocument::class);
    }
}

