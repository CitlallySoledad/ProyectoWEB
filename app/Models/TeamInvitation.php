<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TeamInvitation extends Model
{
    protected $fillable = ['team_id', 'inviter_id', 'email', 'role', 'token', 'status', 'accepted_at'];

    // Relación con Team
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    // Relación con User (quien invita)
    public function inviter()
    {
        return $this->belongsTo(User::class, 'inviter_id');
    }

    // Generar token único
    public static function generateToken()
    {
        return Str::random(64);
    }
}
