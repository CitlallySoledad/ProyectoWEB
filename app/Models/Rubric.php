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

    /**
     * Obtener todos los eventos que tienen esta rúbrica asignada
     */
    public function events()
    {
        return Event::whereJsonContains('rubric_ids', (string)$this->id)->get();
    }

    public function criteria()
    {
        return $this->hasMany(RubricCriterion::class);
    }
}
