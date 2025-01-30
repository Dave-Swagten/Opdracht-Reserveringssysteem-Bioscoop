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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Naam van de persoon die reserveert
            $table->string('email'); // Email voor bevestiging
            $table->foreignId('chair_id')->constrained()->onDelete('cascade'); // Relatie met stoel
            $table->dateTime('screening_time'); // Tijdstip van de film
            $table->string('movie_title'); // Titel van de film
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
