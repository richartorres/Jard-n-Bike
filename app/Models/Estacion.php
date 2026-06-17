<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estacion extends Model
{
    use HasFactory;

    protected $table = 'estaciones';
    protected $primaryKey = 'id_estacion';

    protected $fillable = [
        'codigo',
        'nombre',
        'direccion',
        'coordenadas',
        'capacidad',
        'energia_disp',
        'estado'
    ];

    // Una estación tiene muchas bicicletas vinculadas
    public function bicicletas()
    {
        return $this->hasMany(Bicicleta::class, 'estacion_act', 'id_estacion');
    }
}