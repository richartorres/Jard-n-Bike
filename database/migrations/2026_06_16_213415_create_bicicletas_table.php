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
    Schema::create('bicicletas', function (Blueprint $table) {
        $table->id('id_bicicleta'); // PK
        // FK que se conecta con la tabla estaciones (estacion_act)
        $table->foreignId('estacion_act')->constrained('estaciones', 'id_estacion')->onDelete('cascade'); 
        $table->string('codigo_qr')->unique(); // Para escanear y desbloquear
        $table->string('modelo');
        $table->string('num_serie');
        $table->integer('nivel_bateria')->default(100); // 0% a 100%
        $table->string('estado')->default('Disponible'); // Disponible, En uso, Crítico - Cargar
        $table->decimal('kilometraje', 8, 2)->default(0.00); // Kilometraje acumulado
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bicicletas');
    }
};
