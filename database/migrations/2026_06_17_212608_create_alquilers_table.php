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
        Schema::create('alquilers', function (Blueprint $table) {
            $table->id('id_alquiler');
            
            // Relaciones (Llaves foráneas)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('bicicleta_id')->constrained('bicicletas', 'id_bicicleta')->onDelete('cascade');
            
            // 👈 CORRECCIÓN AQUÍ: Cambiado 'estacions' por 'estaciones'
            $table->foreignId('estacion_origen_id')->constrained('estaciones', 'id_estacion');
            $table->foreignId('estacion_destino_id')->nullable()->constrained('estaciones', 'id_estacion');
            
            // Tiempos del viaje
            $table->dateTime('fecha_inicio');
            $table->dateTime('fecha_fin')->nullable(); 
            
            // Costos y Estado del alquiler
            $table->decimal('valor_total', 10, 2)->default(0.00);
            $table->enum('estado_alquiler', ['Activo', 'Completado', 'Multado'])->default('Activo');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alquilers');
    }
};
