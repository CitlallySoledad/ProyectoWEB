<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    protected $fillable = [
        'project_name',
        'rubric',
        'creativity',
        'functionality',
        'innovation',
        'comments',
        'judge',
        'team',
        'total_score',
        'evaluated_at',
    ];

    protected $casts = [
        'evaluated_at' => 'date',
    ];
}

