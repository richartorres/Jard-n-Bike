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
    Schema::create('estaciones', function (Blueprint $table) {
        $table->id('id_estacion'); // PK sugerida por el requisito
        $table->string('codigo')->unique(); // Ej: EST-001
        $table->string('nombre'); // Ej: Parque Principal
        $table->string('direccion');
        $table->string('coordenadas'); // Para el mapa (Lat, Long)
        $table->integer('capacidad'); // Cantidad máxima de anclajes (RN-03)
        $table->integer('energia_disp')->default(100); // Energía disponible
        $table->string('estado')->default('Activa'); // Activa / Mantenimiento
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estaciones');
    }
};
