<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alquiler extends Model
{
    use HasFactory;

    // Indicamos la llave primaria correcta
    protected $primaryKey = 'id_alquiler';

    // Campos que permitiremos rellenar en masa
    protected $fillable = [
        'user_id',
        'bicicleta_id',
        'estacion_origen_id',
        'estacion_destino_id',
        'fecha_inicio',
        'fecha_fin',
        'valor_total',
        'estado_alquiler',
    ];

  // Relación: Un alquiler pertenece a un Usuario
    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relación: Un alquiler pertenece a una Bicicleta
    public function bicicleta()
    {
        return $this->belongsTo(Bicicleta::class, 'bicicleta_id', 'id_bicicleta');
    }

    // Relación: Estación de origen
    public function estacionOrigen()
    {
        return $this->belongsTo(Estacion::class, 'estacion_origen_id', 'id_estacion');
    }

    // Relación: Estación de destino (puede ser null al iniciar)
    public function estacionDestino()
    {
        return $this->belongsTo(Estacion::class, 'estacion_destino_id', 'id_estacion');
    }
}