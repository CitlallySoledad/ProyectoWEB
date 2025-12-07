<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvaluationEvidence extends Model
{
    /**
     * Nombre de la tabla (evidence es un sustantivo incontable, el plural automático no coincide).
     */
    protected $table = 'evaluation_evidences';

    protected $fillable = [
        'evaluation_id',
        'file_name',
        'file_path',
        'mime_type',
        'file_size',
        'description',
    ];

    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class);
    }
}
