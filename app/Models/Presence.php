<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Presence extends Model
{
        protected $fillable = [
            'employe_id',
            'date_presence',
            'heure_arrivee',
            'heure_depart',
            'latitude_arrivee',
            'longitude_arrivee',
            'latitude_depart',
            'longitude_depart',
            'statut_pointage',
            'duree_minutes',
        ];
        protected function cast(): array
        {
            return[
                'date_presence' => 'date'
            ];
        }
        public function employe()
        {
            return $this->belongTo(Employe::class);
        }
}
