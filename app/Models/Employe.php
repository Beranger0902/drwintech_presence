<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employe extends Model
{
    protected $fillable = [
        'matricule',
        'nom',
        'prenom',
        'email',
        'telephone',
        'poste',
        'departement',
        'date_naissance',
        'date_embauche',
        'statut',
        'user_id',
        'refusals_count',
        'demandes_bloquees',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function presence()
    {
        return $this->hasMany(Presence::class);
    }
    public function demande()
    {
        return $this->hasMany(Demande::class);
    }

}
