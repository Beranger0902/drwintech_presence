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

//Pointage de l'arrivée de l'employé

    public function pointerArrivee(Request $request, GeolocalisationService $geolocalisationService)
    {
        $request->validate([
            'latitude' => ['required', 'numeric'],
            'longitude' => ['required', 'numeric'],
        ]);

        $employe = $request->user()->employe;

        if (! $employe) {
            return back()->with('error', 'Aucune fiche employé liée à cet utilisateur.');
        }

        $latitude = (float) $request->latitude;
        $longitude = (float) $request->longitude;

        if (! $geolocalisationService->positionAutorisee($latitude, $longitude)) {
            return back()->with('error', 'Pointage refusé : vous êtes hors de la zone autorisée.');
        }

        $presence = Presence::firstOrCreate(
            [
                'employe_id' => $employe->id,
                'date_presence' => today(),
            ],
            [
                'statut_pointage' => 'present',
            ]
        );

        if ($presence->heure_arrivee) {
            return back()->with('error', 'Votre arrivée a déjà été pointée aujourd’hui.');
        }

        $presence->update([
            'heure_arrivee' => now()->format('H:i:s'),
            'latitude_arrivee' => $latitude,
            'longitude_arrivee' => $longitude,
            'statut_pointage' => 'present',
        ]);

        return back()->with('success', 'Pointage d’arrivée enregistré avec succès.');
    }

    public function pointerDepart(Request $request, GeolocalisationService $geolocalisationService)
    {
        $request->validate([
            'latitude' => ['required', 'numeric'],
            'longitude' => ['required', 'numeric'],
        ]);

        $employe = $request->user()->employe;

        if (! $employe) {
            return back()->with('error', 'Aucune fiche employé liée à cet utilisateur.');
        }

        $latitude = (float) $request->latitude;
        $longitude = (float) $request->longitude;

        if (! $geolocalisationService->positionAutorisee($latitude, $longitude)) {
            return back()->with('error', 'Pointage refusé : vous êtes hors de la zone autorisée.');
        }

        $presence = Presence::where('employe_id', $employe->id)
            ->whereDate('date_presence', today())
            ->first();

        if (! $presence || ! $presence->heure_arrivee) {
            return back()->with('error', 'Vous devez d’abord pointer votre arrivée.');
        }

        if ($presence->heure_depart) {
            return back()->with('error', 'Votre départ a déjà été pointé aujourd’hui.');
        }

        $heureArrivee = Carbon::createFromFormat('H:i:s', $presence->heure_arrivee);
        $heureDepart = Carbon::now();
        $dureeMinutes = $heureArrivee->diffInMinutes($heureDepart);

        $presence->update([
            'heure_depart' => $heureDepart->format('H:i:s'),
            'latitude_depart' => $latitude,
            'longitude_depart' => $longitude,
            'duree_minutes' => $dureeMinutes,
            'statut_pointage' => 'termine',
        ]);

        return back()->with('success', 'Pointage de départ enregistré avec succès.');
    }
}
