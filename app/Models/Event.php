<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'place',
        'capacity',
        'start_date',
        'end_date',
        'status',
        'category',
        'judge_ids',
        'rubric_ids',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'judge_ids'  => 'array',
        'rubric_ids' => 'array',
    ];

    /**
     * Equipos inscritos en este evento
     */
    public function teams()
    {
        return $this->belongsToMany(Team::class, 'event_team')
            ->withTimestamps();
    }

    /**
     * Jueces asignados al evento
     */
    public function judges()
    {
        return $this->belongsToMany(User::class, 'event_judge', 'event_id', 'judge_id')
            ->wherePivot('judge_id', 'in', $this->judge_ids ?? []);
    }

    /**
     * Obtener objetos de usuarios jueces
     */
    public function getJudgesAttribute()
    {
        if (empty($this->attributes['judge_ids'])) {
            return collect([]);
        }
        
        $judgeIds = json_decode($this->attributes['judge_ids'], true);
        return User::whereIn('id', $judgeIds)->get();
    }

    // ===============================
    // ACCESSOR: ESTADO AUTOMÁTICO
    // ===============================

    /**
     * Obtener el estado actual del evento basado en las fechas
     * Este accessor sobrescribe el atributo 'status' con lógica automática
     */
    public function getStatusAttribute($value)
    {
        // Si el estado en BD es 'borrador', siempre respetar ese estado
        if ($value === 'borrador') {
            return 'borrador';
        }

        $now = now()->startOfDay();
        $startDate = $this->attributes['start_date'] ? \Carbon\Carbon::parse($this->attributes['start_date'])->startOfDay() : null;
        $endDate = $this->attributes['end_date'] ? \Carbon\Carbon::parse($this->attributes['end_date'])->startOfDay() : null;

        // Si no hay fechas definidas, retornar el estado original
        if (!$startDate) {
            return $value;
        }

        // Lógica automática de estados:
        
        // 1. Si la fecha de fin ya pasó → CERRADO
        if ($endDate && $now->gt($endDate)) {
            return 'cerrado';
        }

        // 2. Si la fecha de inicio ya llegó (o pasó) → ACTIVO
        if ($now->gte($startDate)) {
            return 'activo';
        }

        // 3. Si aún falta para que inicie → PUBLICADO
        if ($now->lt($startDate)) {
            return 'publicado';
        }

        // Fallback: retornar el valor original de la BD
        return $value;
    }

    /**
     * Obtener el estado real guardado en la base de datos (sin lógica automática)
     */
    public function getRawStatusAttribute()
    {
        return $this->attributes['status'] ?? 'borrador';
    }

    // ===============================
    // SCOPES
    // ===============================

    /**
     * Scope para eventos activos
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'activo');
    }

    /**
     * Scope para eventos disponibles (publicados y activos, no finalizados)
     */
    public function scopeAvailable($query)
    {
        return $query->whereIn('status', ['publicado', 'activo'])
            ->where(function($q) {
                $q->whereNull('end_date')
                  ->orWhere('end_date', '>=', now());
            });
    }

    /**
     * Scope para eventos que aceptan inscripciones (solo publicados)
     */
    public function scopeAcceptingRegistrations($query)
    {
        return $query->where('status', 'publicado')
            ->where(function($q) {
                $q->whereNull('end_date')
                  ->orWhere('end_date', '>=', now());
            });
    }

    /**
     * Scope para eventos con cupos disponibles
     */
    public function scopeWithSlots($query)
    {
        return $query->whereRaw('capacity IS NULL OR (
            SELECT COUNT(*) FROM event_team WHERE event_team.event_id = events.id
        ) < capacity');
    }

    // ===============================
    // MÉTODOS HELPER
    // ===============================

    /**
     * Verificar si el evento está lleno
     */
    public function isFull()
    {
        if (!$this->capacity) {
            return false; // Sin límite de capacidad
        }
        return $this->teams()->count() >= $this->capacity;
    }

    /**
     * Obtener cupos disponibles
     */
    public function availableSlots()
    {
        if (!$this->capacity) {
            return '∞'; // Sin límite
        }
        $used = $this->teams()->count();
        $available = $this->capacity - $used;
        return max(0, $available);
    }

    /**
     * Verificar si el evento está activo (ya comenzó)
     */
    public function isActive()
    {
        return $this->status === 'activo';
    }

    /**
     * Verificar si el evento está publicado (acepta inscripciones)
     */
    public function isPublished()
    {
        return $this->status === 'publicado';
    }

    /**
     * Verificar si el evento acepta inscripciones actualmente
     */
    public function acceptsInscriptions()
    {
        return $this->status === 'publicado' && !$this->isFull();
    }

    /**
     * Verificar si el evento ha finalizado
     */
    public function hasEnded()
    {
        if (!$this->end_date) {
            return false;
        }
        return $this->end_date->isPast();
    }

    /**
     * Verificar si el evento acepta inscripciones
     */
    public function acceptsRegistrations()
    {
        return $this->isPublished() 
            && !$this->hasEnded() 
            && !$this->isFull();
    }

    /**
     * Obtener el badge de estado con color
     */
    public function getStatusBadge()
    {
        $badges = [
            'borrador' => '<span class="badge bg-secondary">Borrador</span>',
            'publicado' => '<span class="badge bg-info">Publicado</span>',
            'activo' => '<span class="badge bg-success">Activo</span>',
            'cerrado' => '<span class="badge bg-danger">Cerrado</span>',
        ];

        return $badges[$this->status] ?? '<span class="badge bg-secondary">' . ucfirst($this->status) . '</span>';
    }
}
