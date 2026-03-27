<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Conge extends Model
{
    protected $fillable = [
        'demande_id',
        'date_debut',
        'date_fin',
        'type_conge',
        'piece_jointe',
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
    ];

    public function demande()
    {
        return $this->belongsTo(Demande::class);
    }

    public function getNombreJoursAttribute(): int
    {
        return Carbon::parse($this->date_debut)->diffInDays(Carbon::parse($this->date_fin)) + 1;
    }
}
