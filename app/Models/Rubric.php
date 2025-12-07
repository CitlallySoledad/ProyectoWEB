<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rubric extends Model
{
    protected $fillable = [
        'name',
        'event_id',
        'status',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function criteria()
    {
        return $this->hasMany(RubricCriterion::class);
    }
}
