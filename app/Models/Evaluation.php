<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    use HasFactory;
    protected $fillable = [
        'project_id',
        'project_name',
        'rubric_id',
        'judge_id',
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

    // Override getAttribute to handle conflicting column names
    public function getAttribute($key)
    {
        // For these specific keys, always force use of relations instead of columns
        if (in_array($key, ['rubric', 'judge', 'team'])) {
            // If relation is loaded but NULL, reload it
            if ($this->relationLoaded($key)) {
                $relation = $this->relations[$key] ?? null;
                if ($relation === null && $this->{$key . '_id'}) {
                    // Reload the relation
                    $this->unsetRelation($key);
                    return $this->getRelationValue($key);
                }
                return $relation;
            }
            return $this->getRelationValue($key);
        }
        
        return parent::getAttribute($key);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function rubric()
    {
        return $this->belongsTo(Rubric::class, 'rubric_id');
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
