<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    protected $fillable = [
        'project_id',
        'project_name',
        'rubric_id',
        'judge_id',
        'team_id',
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
        'evaluated_at' => 'datetime',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function rubric()
    {
        return $this->belongsTo(Rubric::class);
    }

    public function judge()
    {
        return $this->belongsTo(User::class, 'judge_id');
    }

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    public function evidences()
    {
        return $this->hasMany(EvaluationEvidence::class);
    }

    public function scores()
    {
        return $this->hasMany(EvaluationScore::class);
    }
}
