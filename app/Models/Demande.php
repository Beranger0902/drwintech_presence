<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Demande extends Model
{
    protected $fillable = [
        'employe_id',
        'type_demande',
        'motif',
        'observation',
        'date_soumission',
        'date_validation',
        'statut',
    ];

    protected $casts = [
        'date_soumission' => 'datetime',
        'date_validation' => 'datetime',
    ];

    public function employe()
    {
        return $this->belongsTo(Employe::class);
    }

    public function conge()
    {
        return $this->hasOne(Conge::class);
    }

     public function permission()
    {
        return $this->hasOne(Permission::class);
    }
}
