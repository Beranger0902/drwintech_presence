<?php

namespace App\Http\Controllers\Employe;

use App\Http\Controllers\Controller;
use App\Services\GeolocalisationService;
use App\Models\Presence;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;


class PointageController extends Controller
{
    
    public function index(Request $request)
    {
        $employe = $request->user()->employe;

        $presenceDuJour = null;

        if ($employe) {
            $presenceDuJour = Presence::where('employe_id', $employe->id)
                ->whereDate('date_presence', today())
                ->first();
        }

        return view('employe.pointage.index', compact('presenceDuJour'));
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

            $latitude = (float) $request->latitude;
            $longitude = (float) $request->longitude;

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
}
