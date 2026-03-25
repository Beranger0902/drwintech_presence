<?php

namespace App\Services;

class GeolocalisationService
{
    public function calculerDistanceMetres(
        float $latitude1,
        float $longitude1,
        float $latitude2,
        float $longitude2
    ): float {
        $rayonTerre = 6371000; // mètres

        $lat1 = deg2rad($latitude1);
        $lon1 = deg2rad($longitude1);
        $lat2 = deg2rad($latitude2);
        $lon2 = deg2rad($longitude2);

        $deltaLat = $lat2 - $lat1;
        $deltaLon = $lon2 - $lon1;

        $a = sin($deltaLat / 2) * sin($deltaLat / 2)
            + cos($lat1) * cos($lat2)
            * sin($deltaLon / 2) * sin($deltaLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $rayonTerre * $c;
    }

    public function positionAutorisee(float $latitude, float $longitude): bool
    {
        $latitudeEntreprise = (float) env('DRWINTECH_LATITUDE');
        $longitudeEntreprise = (float) env('DRWINTECH_LONGITUDE');
        $rayonAutorise = (float) env('DRWINTECH_RAYON_METRES', 15);

        $distance = $this->calculerDistanceMetres(
            $latitude,
            $longitude,
            $latitudeEntreprise,
            $longitudeEntreprise
        );

        return $distance <= $rayonAutorise;
    }
}