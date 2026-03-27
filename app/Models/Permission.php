<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = [
        'demande_id',
        'date_permission',
        'heure_debut',
        'heure_fin',
    ];

    protected $casts = [
        'date_permission' => 'date',
    ];

    public function demande()
    {
        return $this->belongsTo(Demande::class);
    }

    public function getDureeAttribute()
    {
        return Carbon::parse($this->heure_debut)
            ->diffInMinutes(Carbon::parse($this->heure_fin));
    }
}
