<?php

namespace App\Http\Controllers\Employe;

use App\Http\Controllers\Controller;
use App\Services\GeolocalisationService;
use App\Models\Demande;
use App\Models\JourFerie;
use Carbon\Carbon;
use App\Models\Presence;
use Illuminate\Http\Request;



class PointageController extends Controller
{
        private function creerAbsenceSiNecessaire($employe): void
    {
        if (! $employe) {
            return;
        }

        $presenceDuJour = Presence::where('employe_id', $employe->id)
            ->whereDate('date_presence', today())
            ->first();

        if ($presenceDuJour) {
            return;
        }

        $heureFin = config('pointage.heure_max_depart');
        [$heure, $minute] = explode(':', $heureFin);

        $heureLimiteAbsence = now()->copy()->setTime((int) $heure, (int) $minute, 0);

        if (! now()->greaterThan($heureLimiteAbsence)) {
            return;
        }

        $demandePermission = Demande::with('permission')
            ->where('employe_id', $employe->id)
            ->where('type_demande', 'permission')
            ->where('statut', 'en_attente')
            ->get()
            ->first(function ($demande) {
                return $demande->permission
                    && $demande->permission->date_permission
                    && Carbon::parse($demande->permission->date_permission)->isToday();
            });



        $statut = 'absent';

        if ($this->estWeekend()) {
            $statut = 'weekend';
        } else {
            $jourFerie = $this->recupererJourFerieDuJour();

            if ($jourFerie) {
                $statut = 'ferie';
            } else {
                $conge = $this->recupererCongeActif($employe);

                if ($conge) {
                    $statut = 'conge';
                }
            }
        }


       if ($statut === 'absent' && $demandePermission && $demandePermission->permission) {
            $permission = $demandePermission->permission;

            $heureDebutTravail = config('pointage.heure_debut', '08:30');
            $heureFinTravail = config('pointage.heure_fin', '18:30');

            $permissionJourneeEntiere = $permission->heure_debut <= $heureDebutTravail
                && $permission->heure_fin >= $heureFinTravail;

            if ($permissionJourneeEntiere) {
                $statut = 'absent_justifie';
            }
        }

        Presence::create([
            'employe_id' => $employe->id,
            'date_presence' => today(),
            'statut_pointage' => $statut,
        ]);
    }


    private function statutBloqueCalculTemps(?string $statut): bool
    {
        return in_array($statut, ['absent', 'absent_justifie'], true);
    }



    private function estWeekend(Carbon $date = null): bool
    {
        $date = $date ?? now();

        return $date->isSaturday() || $date->isSunday();
    }

    private function recupererJourFerieDuJour()
    {
        return JourFerie::whereDate('date_ferie', today())->first();
    }


    private function recupererCongeActif($employe)
    {
        return Demande::with('conge')
            ->where('employe_id', $employe->id)
            ->where('type_demande', 'conge')
            ->get()
            ->first(function ($demande) {
                return $demande->conge
                    && Carbon::today()->between(
                        $demande->conge->date_debut,
                        $demande->conge->date_fin
                    );
            });
    }



    //Chargement des données de l'employé depuis la base de donnée pour pouvoir l'afficher au niveau du profil
    public function index(Request $request)
    {
        $employe = $request->user()->employe;
        $this->creerAbsenceSiNecessaire($employe);
        $presenceDuJour = null;

        if ($employe) {
            $presenceDuJour = \App\Models\Presence::where('employe_id', $employe->id)
                ->whereDate('date_presence', today())
                ->first();
        }
        // Recupérer les données et envoyer au fichier Views pour l'affichage
        return view('employe.pointage.index', compact('employe','presenceDuJour'));
    }



// Recuperer la permission du jour de l'employe pour définir un status  
    private function recupererPermissionDuJour($employe)
    {
        return Demande::with('permission')
            ->where('employe_id', $employe->id)
            ->where('type_demande', 'permission')
            ->where('statut', 'approuver')
            ->get()
            ->first(function ($demande) {
                return $demande->permission
                    && $demande->permission->date_permission
                    && Carbon::parse($demande->permission->date_permission)->isToday();
            });
    }

//Lorsque la permission est couvre toute la journée alors le statuts est considérer comme absence justifié 
    private function permissionCouvreTouteLaJournee($permission): bool
    {
        if (! $permission) {
            return false;
        }

        $heureDebutTravail = config('pointage.heure_debut', '08:30');
        $heureFinTravail = config('pointage.heure_fin', '18:30');

        return $permission->heure_debut <= $heureDebutTravail
            && $permission->heure_fin >= $heureFinTravail;
    }

//Lorsque la permission est demander mais que l'employe vient continuer la journée après la permission (permission partiel)
        private function heureDansPlagePermission($permission, Carbon $maintenant): bool
    {
        if (! $permission) {
            return false;
        }

        $heureActuelle = $maintenant->format('H:i');
        $heureDebut = Carbon::parse($permission->heure_debut)->format('H:i');
        $heureFin = Carbon::parse($permission->heure_fin)->format('H:i');

        return $heureActuelle >= $heureDebut && $heureActuelle <= $heureFin;
    }


        private function jsonResponse(bool $success, string $message, array $data = [], int $status = 200)
    {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    private function determinerStatutArrivee(\Carbon\Carbon $heureArrivee): string
    {
       $heureDebut = config('pointage.heure_debut');
        [$heure, $minute] = explode(':', $heureDebut);

        $heureLimite = now()->copy()->setTime((int) $heure, (int) $minute, 0);

        return $heureArrivee->lessThanOrEqualTo($heureLimite) ? 'present' : 'retard';
    }



//Pointage de l'arrivée de l'employé

          public function pointerArrivee(Request $request, GeolocalisationService $geolocalisationService)
    {
        $request->validate([
            'latitude' => ['required', 'numeric'],
            'longitude' => ['required', 'numeric'],
        ]);

        $employe = $request->user()->employe;

        if (! $employe) {
            return $this->jsonResponse(false, 'Aucune fiche employé liée à cet utilisateur.', [], 422);
        }

// Refuser lorsqu'il veut faire le pointage un weekend ou jours férie

        if ($this->estWeekend()) {
            return $this->jsonResponse(false, 'Aujourd’hui est un week-end. Il n’y a pas de travail prévu.', [], 422);
        }

        $jourFerie = $this->recupererJourFerieDuJour();

        if ($jourFerie) {
            return $this->jsonResponse(false, 'Aujourd’hui est un jour férié : ' . $jourFerie->libelle . '.', [], 422);
        }


        $conge = $this->recupererCongeActif($employe);

        if ($conge) {
            return $this->jsonResponse(false, 'Vous êtes actuellement en congé. Votre période de congé n’est pas terminée.', [], 422);
        }


        $latitude = (float) $request->latitude;
        $longitude = (float) $request->longitude;

        if (! $geolocalisationService->positionAutorisee($latitude, $longitude)) {
            return $this->jsonResponse(false, 'Pointage refusé : vous êtes hors de la zone autorisée.', [], 422);
        }

        $presence = Presence::firstOrCreate(
            [
                'employe_id' => $employe->id,
                'date_presence' => today(),
            ]
        );

        if ($presence->heure_arrivee) {
            return $this->jsonResponse(false, 'Votre arrivée a déjà été pointée aujourd’hui.', [], 422);
        }

        $maintenant = now();

        $demandePermission = $this->recupererPermissionDuJour($employe);
        $permission = $demandePermission?->permission;

        if ($permission && $this->permissionCouvreTouteLaJournee($permission)) {
            return $this->jsonResponse(false, 'Vous avez une permission couvrant toute la journée. Aucun pointage normal n’est attendu.', [], 422);
        }

      //  if ($permission && $this->heureDansPlagePermission($permission, $maintenant)) {
        //    return $this->jsonResponse(false, 'Votre permission est encore en cours. Vous pourrez pointer après la fin de votre permission.', [], 422);
        //}

        $heure = $maintenant->format('H:i:s');
        $statutArrivee = $this->determinerStatutArrivee($maintenant);

        $presence->update([
            'heure_arrivee' => $heure,
            'latitude_arrivee' => $latitude,
            'longitude_arrivee' => $longitude,
            'statut_pointage' => $statutArrivee,
        ]);

        return $this->jsonResponse(true, 'Pointage d’arrivée enregistré avec succès.', [
            'heure' => $heure,
            'type' => 'arrivee',
            'latitude' => $latitude,
            'longitude' => $longitude,
            'statut' => $statutArrivee,
        ]);
    }

    
        // Pointage de départ de l'employé
      public function pointerDepart(Request $request, GeolocalisationService $geolocalisationService)
    {
        $request->validate([
            'latitude' => ['required', 'numeric'],
            'longitude' => ['required', 'numeric'],
        ]);

        $employe = $request->user()->employe;

        if (! $employe) {
            return $this->jsonResponse(false, 'Aucune fiche employé liée à cet utilisateur.', [], 422);
        }



        if ($this->estWeekend()) {
            return $this->jsonResponse(false, 'Aujourd’hui est un week-end. Il n’y a pas de travail prévu.', [], 422);
        }



        $jourFerie = $this->recupererJourFerieDuJour();

        if ($jourFerie) {
            return $this->jsonResponse(false, 'Aujourd’hui est un jour férié : ' . $jourFerie->libelle . '.', [], 422);
        }



        $conge = $this->recupererCongeActif($employe);

        if ($conge) {
            return $this->jsonResponse(false, 'Vous êtes actuellement en congé. Votre période de congé n’est pas terminée.', [], 422);
        }
        

        $latitude = (float) $request->latitude;
        $longitude = (float) $request->longitude;

        if (! $geolocalisationService->positionAutorisee($latitude, $longitude)) {
            return $this->jsonResponse(false, 'Pointage refusé : vous êtes hors de la zone autorisée.', [], 422);
        }

        $presence = Presence::where('employe_id', $employe->id)
            ->whereDate('date_presence', today())
            ->first();

        if (! $presence) {
            return $this->jsonResponse(false, 'Aucune présence trouvée pour aujourd’hui.', [], 422);
        }



        if (! $presence->heure_arrivee) {
            return $this->jsonResponse(false, 'Vous devez d’abord pointer votre arrivée.', [], 422);
        }

        if ($presence->heure_depart) {
            return $this->jsonResponse(false, 'Votre départ a déjà été pointé aujourd’hui.', [], 422);
        }

        //Calculer du travail pour le départ

        $heureArrivee = \Carbon\Carbon::createFromFormat('H:i:s', $presence->heure_arrivee);
        $heureDepart = now();

        $heureFinTravail = \Carbon\Carbon::createFromFormat('H:i', config('pointage.heure_fin'));
        $heureMaxDepart = \Carbon\Carbon::createFromFormat('H:i', config('pointage.heure_max_depart'));

      
      /**Bloquer le calcule si c'est un jour ferié, weekend et congé */

        if (in_array($presence->statut_pointage, ['conge', 'ferie', 'weekend'])) {
            return $this->jsonResponse(false, 'Cette journée ne permet pas de calculer un temps de travail.', [], 422);
        }
      
      
        /**
         * ❌ CAS BLOQUANT : après 20h → refus
         */
        if ($heureDepart->greaterThan($heureMaxDepart)) {
            return $this->jsonResponse(false, 'Pointage refusé : vous avez dépassé l’heure limite de 20h.', [], 422);
        }

        /**
         * 🔢 Calcul du temps total
         */
        $dureeMinutes = $heureArrivee->diffInMinutes($heureDepart);

        /**
         * 🟢 Temps normal (max jusqu’à 18h30)
         */
        $heureFinEffective = $heureDepart->lessThan($heureFinTravail)
            ? $heureDepart
            : $heureFinTravail;

        $dureeNormale = $heureArrivee->lessThan($heureFinEffective)
            ? $heureArrivee->diffInMinutes($heureFinEffective)
            : 0;

        /**
         * 🔵 Heures supplémentaires
         */
        $dureeSupplementaire = 0;

        if ($heureDepart->greaterThan($heureFinTravail)) {
            $dureeSupplementaire = $heureFinTravail->diffInMinutes($heureDepart);
        }


        $presence->update([
            'heure_depart' => $heureDepart->format('H:i:s'),
            'latitude_depart' => $latitude,
            'longitude_depart' => $longitude,
            'duree_minutes' => $dureeMinutes,
            'duree_normale' => $dureeNormale,
            'heures_supplementaires' => $dureeSupplementaire,
            'statut_pointage' => 'termine',
        ]);

        return $this->jsonResponse(true, 'Pointage de départ enregistré avec succès.', [
            'heure' => $heure,
            'type' => 'depart',
            'latitude' => $latitude,
            'longitude' => $longitude,
            'duree_minutes' => $dureeMinutes,
        ]);
    }




     //L'historique du pointage pourvoir avoir une vue global

    public function historique(Request $request)
    {
        $employe = $request->user()->employe;
        $this->creerAbsenceSiNecessaire($employe);
        if (! $employe) {
            abort(404, 'Employé introuvable.');
        }

        $query = \App\Models\Presence::where('employe_id', $employe->id);

        if ($request->filled('date_debut')) {
            $query->whereDate('date_presence', '>=', $request->date_debut);
        }

        if ($request->filled('date_fin')) {
            $query->whereDate('date_presence', '<=', $request->date_fin);
        }

        if ($request->filled('statut')) {
            $query->where('statut_pointage', $request->statut);
        }

        $historiques = $query
            ->orderByDesc('date_presence')
            ->paginate(10)
            ->withQueryString();

        return view('employe.historique.index', compact('employe', 'historiques'));
    }




    //Page du temps de travail

    public function tempsTravail(Request $request)
    {
        $employe = $request->user()->employe;
        $this->creerAbsenceSiNecessaire($employe);

        if (! $employe) {
            abort(404, 'Employé introuvable.');
        }

        $periode = $request->get('periode', 'semaine');

        $joursLabels = [];
        $heuresParJour = [];
        $titreGraphique = '';
        $presences = collect();

        if ($periode === 'mois') {
            $debut = now()->startOfMonth();
            $fin = now()->endOfMonth();
            $titreGraphique = 'Heures de travail du mois';

            $presences = Presence::where('employe_id', $employe->id)
                ->whereNotIn('statut_pointage', [
                    'absent',
                    'absent_justifie',
                    'conge',
                    'ferie',
                    'weekend'
                ])
                ->whereBetween('date_presence', [$debut->toDateString(), $fin->toDateString()])
                ->orderBy('date_presence')
                ->get();

            $nombreJours = now()->daysInMonth;

            for ($i = 1; $i <= $nombreJours; $i++) {
                $date = now()->copy()->startOfMonth()->day($i)->toDateString();
                $presence = $presences->firstWhere('date_presence', $date);
                $minutes = $presence?->duree_minutes ?? 0;

                $joursLabels[] = (string) $i;
                $heuresParJour[] = round($minutes / 60, 2);
            }
        } else {
            $debut = now()->startOfWeek();
            $fin = now()->endOfWeek();
            $titreGraphique = 'Heures de travail par jour';

            $presences = Presence::where('employe_id', $employe->id)
                ->whereBetween('date_presence', [$debut->toDateString(), $fin->toDateString()])
                ->orderBy('date_presence')
                ->get();

            $labelsFixes = ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'];

            for ($i = 0; $i < 7; $i++) {
                $date = now()->startOfWeek()->copy()->addDays($i)->toDateString();
                $presence = $presences->firstWhere('date_presence', $date);
                $minutes = $presence?->duree_minutes ?? 0;

                $joursLabels[] = $labelsFixes[$i];
                $heuresParJour[] = round($minutes / 60, 2);
            }
        }

        $totalMinutesPeriode = $presences->sum('duree_minutes');

        $presencesMois = Presence::where('employe_id', $employe->id)
            ->whereMonth('date_presence', now()->month)
            ->whereYear('date_presence', now()->year)
            ->get();

        $totalMinutesMois = $presencesMois->sum('duree_minutes');
        $joursTravailles = $presences->filter(fn ($p) => !empty($p->heure_arrivee))->count();

        $minutesNormales = min($totalMinutesPeriode, 40 * 60);
        $minutesSupp = max($totalMinutesPeriode - (40 * 60), 0);

        $stats = [
            'periode' => $periode,
            'heures_periode' => $this->formatMinutes($totalMinutesPeriode),
            'heures_mois' => $this->formatMinutes($totalMinutesMois),
            'heures_supp' => $this->formatMinutes($minutesSupp),
            'jours_travailles' => $joursTravailles . ' jours',
            'heures_par_jour' => $heuresParJour,
            'jours_labels' => $joursLabels,
            'titre_graphique' => $titreGraphique,
            'pourcentage_normal' => $totalMinutesPeriode > 0 ? round(($minutesNormales / $totalMinutesPeriode) * 100) : 0,
            'pourcentage_supp' => $totalMinutesPeriode > 0 ? round(($minutesSupp / $totalMinutesPeriode) * 100) : 0,


             'presents' => Presence::where('employe_id', $employe->id)
                ->whereIn('statut_pointage', ['present', 'termine'])
                ->count(),

            'retards' => Presence::where('employe_id', $employe->id)
                ->where('statut_pointage', 'retard')
                ->count(),

            'absents' => Presence::where('employe_id', $employe->id)
                ->where('statut_pointage', 'absent')
                ->count(),

            'absences_justifiees' => Presence::where('employe_id', $employe->id)
                ->where('statut_pointage', 'absent_justifie')
                ->count(),
        ];

        return view('employe.temps-travail.index', compact('employe', 'stats'));
    }


    
    private function formatMinutes(?int $minutes): string
    {
        $minutes = $minutes ?? 0;
        $heures = intdiv($minutes, 60);
        $reste = $minutes % 60;

        return $heures . 'h ' . str_pad((string) $reste, 2, '0', STR_PAD_LEFT) . 'min';
    }
}
