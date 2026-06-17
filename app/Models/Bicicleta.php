<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bicicleta extends Model
{
    use HasFactory;

    protected $table = 'bicicletas';
    protected $primaryKey = 'id_bicicleta';

    protected $fillable = [
        'estacion_act',
        'codigo_qr',
        'modelo',
        'num_serie',
        'nivel_bateria',
        'estado',
        'kilometraje'
    ];

    // Una bicicleta pertenece a una estación
    public function estacion()
    {
        return $this->belongsTo(Estacion::class, 'estacion_act', 'id_estacion');
    }
}