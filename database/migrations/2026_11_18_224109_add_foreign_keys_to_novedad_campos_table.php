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
        Schema::table('novedad_campos', function (Blueprint $table) {
            // Aseguramos que las columnas existan y sean nullable
            $table->unsignedBigInteger('id_bicicleta')->nullable()->change();
            $table->unsignedBigInteger('id_estacion')->nullable()->change();

            // Creamos la relación real en MySQL/MariaDB
            $table->foreign('id_bicicleta')
                ->references('id_bicicleta') // O 'id' según como se llame en tu tabla bicicletas
                ->on('bicicletas')
                ->nullOnDelete();

            $table->foreign('id_estacion')
                ->references('id_estacion') // O 'id' según como se llame en tu tabla estaciones
                ->on('estaciones')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('novedad_campos', function (Blueprint $table) {
            //
        });
    }
};
