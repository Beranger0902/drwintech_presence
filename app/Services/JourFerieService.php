<?php

namespace App\Services;


use Illuminate\Support\Facades\Http;

class JourFerieService
{
    public function estFerie($date)
    {
        $year = date('Y', strtotime($date));

        $response = Http::get("https://date.nager.at/api/v3/PublicHolidays/$year/BJ");

        if ($response->failed()) {
            return null;
        }

        $jours = $response->json();

        foreach ($jours as $jour) {
            if ($jour['date'] === $date) {
                return $jour['localName'];
            }
        }

        return null;
    }
}