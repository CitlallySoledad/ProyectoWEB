<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    protected $fillable = [
        'project_id',
        'rubric_id',
        'judge_id',
        'team_id',          // ⚠️ mejor usar team_id en lugar de "team"
        'creativity',
        'functionality',
        'innovation',
        'comments',
        'total_score',
        'evaluated_at',
        'status',
        'final_score',
        'general_comments',
    ];

    protected $casts = [
        'evaluated_at' => 'datetime', // más preciso que 'date'
    ];

    // Relación con el proyecto
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    // Relación con la rúbrica
    public function rubric()
    {
        return $this->belongsTo(Rubric::class);
    }

    // Relación con el juez (usuario)
    public function judge()
    {
        return $this->belongsTo(User::class, 'judge_id');
    }

    // Relación con el equipo (si lo manejas directamente)
    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    // Relación con los puntajes detallados
    public function scores()
    {
        return $this->hasMany(EvaluationScore::class);
    }
}
