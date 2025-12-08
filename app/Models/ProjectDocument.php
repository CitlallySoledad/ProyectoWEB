<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectDocument extends Model
{
    protected $fillable = [
        'project_id',
        'file_name',
        'file_path',
        'original_name',
        'file_size',
        'description',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
