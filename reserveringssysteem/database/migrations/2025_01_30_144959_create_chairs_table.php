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
        Schema::create('chairs', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // stoel
            $table->integer('row_number'); // rijnummer
            $table->integer('seat_number'); // stoelnummer
            $table->boolean('is_available')->default(true);
            $table->decimal('price', 8, 2); // prijs van de stoel
            $table->timestamps();
            
            // Unieke combinatie van rij en stoelnummer
            $table->unique(['row_number', 'seat_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chairs');
    }
};
