<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SyncJoursFeries extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'feries:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchroniser les jours fériés';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $service = new JourFerieService();

        $annee = now()->year;

        $service->synchroniser($annee);

        $this->info("Jours fériés synchronisés pour $annee");
    }
}
