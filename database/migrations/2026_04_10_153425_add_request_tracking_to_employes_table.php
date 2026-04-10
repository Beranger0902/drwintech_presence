<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('employes', function (Blueprint $table) {
            $table->integer('refusals_count')->default(0)->comment('Nombre de demandes refusées consécutives');
            $table->boolean('demandes_bloquees')->default(false)->comment('Demandes bloquées après 3 refus');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employes', function (Blueprint $table) {
            $table->dropColumn(['refusals_count', 'demandes_bloquees']);
        });
    }
};
