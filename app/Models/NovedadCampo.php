<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NovedadCampo extends Model
{
    use HasFactory;

    protected $table = 'novedad_campos';
    protected $primaryKey = 'id'; // Llave primaria estándar de la tabla
    public $timestamps = true;

    protected $fillable = [
        'id_bicicleta',
        'id_estacion',
        'user_id',
        'tipo_novedad',
        'descripcion'
    ];

    // Relación con Bicicleta (usando la columna id_bicicleta)
    public function bicicleta()
    {
        return $this->belongsTo(Bicicleta::class, 'id_bicicleta', 'id');
    }

    // Relación con Estación (usando la columna id_estacion)
    public function estacion()
    {
        return $this->belongsTo(Estacion::class, 'id_estacion', 'id_estacion');
    }

    // Relación con Usuario (usando la columna user_id)
    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}