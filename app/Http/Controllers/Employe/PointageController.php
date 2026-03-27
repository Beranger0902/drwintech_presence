<?php

namespace App\Http\Controllers\Employe;

use App\Http\Controllers\Controller;
use App\Services\GeolocalisationService;
use App\Models\Presence;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;


class PointageController extends Controller
{
    //Chargement des données de l'employé depuis la base de donnée pour pouvoir l'afficher au niveau du profil
    public function index(Request $request)
    {
        $employe = $request->user()->employe;

        $presenceDuJour = null;

        if ($employe) {
            $presenceDuJour = \App\Models\Presence::where('employe_id', $employe->id)
                ->whereDate('date_presence', today())
                ->first();
        }
        // Recupérer les données et envoyer au fichier Views pour l'affichage
        return view('employe.pointage.index', compact('employe','presenceDuJour'));
    }

        private function jsonResponse(bool $success, string $message, array $data = [], int $status = 200)
    {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data' => $data,
        ], $status);
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
        // Recupération du longitude et latitude de la position de l'employé
            $latitude = (float) $request->latitude;
            $longitude = (float) $request->longitude;

    // Comparaison des données recupérer par rapport à la zone exiger

            if (! $geolocalisationService->positionAutorisee($latitude, $longitude)) {
                return $this->jsonResponse(false, 'Pointage refusé : vous êtes hors de la zone autorisée.', [], 422);
            }

            $presence = \App\Models\Presence::firstOrCreate(
                [
                    'employe_id' => $employe->id,
                    'date_presence' => today(),
                ],
                [
                    'statut_pointage' => 'present',
                ]
            );

            //Refus de deux pontage dans la même journée
            if ($presence->heure_arrivee) {
                return $this->jsonResponse(false, 'Votre arrivée a déjà été pointée aujourd’hui.', [], 422);
            }

            $heure = now()->format('H:i:s');

            $presence->update([
                'heure_arrivee' => $heure,
                'latitude_arrivee' => $latitude,
                'longitude_arrivee' => $longitude,
                'statut_pointage' => 'present',
            ]);

            return $this->jsonResponse(true, 'Pointage d’arrivée enregistré avec succès.', [
                'heure' => $heure,
                'type' => 'arrivee',
                'latitude' => $latitude,
                'longitude' => $longitude,
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

        $latitude = (float) $request->latitude;
        $longitude = (float) $request->longitude;

        if (! $geolocalisationService->positionAutorisee($latitude, $longitude)) {
            return $this->jsonResponse(false, 'Pointage refusé : vous êtes hors de la zone autorisée.', [], 422);
        }

        $presence = \App\Models\Presence::where('employe_id', $employe->id)
            ->whereDate('date_presence', today())
            ->first();

        if (! $presence || ! $presence->heure_arrivee) {
            return $this->jsonResponse(false, 'Vous devez d’abord pointer votre arrivée.', [], 422);
        }

        if ($presence->heure_depart) {
            return $this->jsonResponse(false, 'Votre départ a déjà été pointé aujourd’hui.', [], 422);
        }

        $heureArrivee = \Illuminate\Support\Carbon::createFromFormat('H:i:s', $presence->heure_arrivee);
        $heureDepart = \Illuminate\Support\Carbon::now();
        $dureeMinutes = $heureArrivee->diffInMinutes($heureDepart);
        $heure = $heureDepart->format('H:i:s');

        $presence->update([
            'heure_depart' => $heure,
            'latitude_depart' => $latitude,
            'longitude_depart' => $longitude,
            'duree_minutes' => $dureeMinutes,
            'statut_pointage' => 'termine',
        ]);

        return $this->jsonResponse(true, 'Pointage de départ enregistré avec succès.', [
            'heure' => $heure,
            'type' => 'depart',
            'latitude' => $latitude,
            'longitude' => $longitude,
        ]);
    }

    //L'historique du pointage pourvoir avoir une vue global

    public function historique(Request $request)
    {
        $employe = $request->user()->employe;

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

            $presences = \App\Models\Presence::where('employe_id', $employe->id)
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

            $presences = \App\Models\Presence::where('employe_id', $employe->id)
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

        $presencesMois = \App\Models\Presence::where('employe_id', $employe->id)
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
