namespace App\Services;

use App\Models\JourFerie;
use Illuminate\Support\Facades\Http;

class JourFerieService
{
    public function synchroniser($annee)
    {
        $response = Http::get("https://date.nager.at/api/v3/PublicHolidays/$annee/BJ");

        if ($response->failed()) {
            return false;
        }

        $jours = $response->json();

        foreach ($jours as $jour) {
            JourFerie::updateOrCreate(
                ['date_ferie' => $jour['date']],
                [
                    'libelle' => $jour['localName'],
                    'annee' => $annee
                ]
            );
        }

        return true;
    }

    public function estFerie($date)
    {
        return JourFerie::whereDate('date_ferie', $date)->first();
    }
}