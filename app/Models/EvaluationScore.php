<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EvaluationScore extends Model
{
    use HasFactory;

    protected $fillable = [
        'evaluation_id',
        'rubric_criterion_id',
        'score',
        'comment',
    ];

    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class);
    }

    public function criterion()
    {
        return $this->belongsTo(RubricCriterion::class, 'rubric_criterion_id');
    }
}
