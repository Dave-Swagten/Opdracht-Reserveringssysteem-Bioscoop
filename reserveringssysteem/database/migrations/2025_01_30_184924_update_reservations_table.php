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
            // Verwijder de unieke constraint van reservation_code
            $table->dropUnique(['reservation_code']);
            
            // Voeg een index toe voor sneller zoeken op reservation_code
            $table->index('reservation_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            // Herstel de unieke constraint
            $table->unique('reservation_code');
            
            // Verwijder de index
            $table->dropIndex(['reservation_code']);
        });
    }
};
