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
        Schema::create('presences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employe_id')->constrained()->onDelete('cascade');

            $table->date('date_presence');
            $table->time('heure_arrivee')->nullable();
            $table->time('heure_depart')->nullable();

            $table->decimal('latitude_arrivee', 10, 8)->nullable();
            $table->decimal('longitude_arrivee', 11, 8)->nullable();

            $table->decimal('latitude_depart', 10, 8)->nullable();
            $table->decimal('longitude_depart', 11, 8)->nullable();

            $table->string('statut_pointage')->default('en_attente');
            $table->integer('duree_minutes')->nullable();
            $table->timestamps();
            $table->unique(['employe_id', 'date_presence']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presences');
    }
};
