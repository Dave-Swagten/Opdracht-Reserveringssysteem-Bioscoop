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
        Schema::table('reservations', function (Blueprint $table) {
            // Verwijder oude kolommen
            $table->dropColumn(['screening_time', 'movie_title']);
            
            // Voeg nieuwe kolommen toe
            $table->foreignId('screening_id')->constrained()->onDelete('cascade');
            $table->decimal('price', 8, 2)->comment('Prijs op moment van reservering');
            $table->string('reservation_code', 8)->unique()->comment('Unieke reserveringscode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            // Herstel oude kolommen
            $table->dateTime('screening_time');
            $table->string('movie_title');
            
            // Verwijder nieuwe kolommen
            $table->dropForeign(['screening_id']);
            $table->dropColumn(['screening_id', 'price', 'reservation_code']);
        });
    }
};
