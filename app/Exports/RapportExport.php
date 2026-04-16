<?php
namespace App\Exports;

use App\Models\Presence;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;

class RapportExport implements FromCollection
{
    protected $dateDebut, $dateFin, $employeId;

    public function __construct($dateDebut, $dateFin, $employeId)
    {
        $this->dateDebut = $dateDebut;
        $this->dateFin = $dateFin;
        $this->employeId = $employeId;
    }

    public function collection()
    {
        $query = Presence::with('employe')
            ->whereBetween('date_presence', [$this->dateDebut, $this->dateFin]);

        if ($this->employeId) {
            $query->where('employe_id', $this->employeId);
        }

        return $query->get()->map(function ($p) {
            return [
                'Nom' => $p->employe->prenom . ' ' . $p->employe->nom,
                'Date' => $p->date_presence,
                'Arrivée' => $p->heure_arrivee,
                'Départ' => $p->heure_depart,
                'Statut' => $p->statut_pointage
            ];
        });
    }
}