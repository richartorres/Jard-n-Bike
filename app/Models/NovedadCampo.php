<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NovedadCampo extends Model
{
    use HasFactory;

    protected $table = 'novedad_campos';
    protected $primaryKey = 'id'; // O 'id_novedad' si ese es el nombre exacto de tu llave primaria en la BD
    
    // Si tu tabla tiene columnas created_at y updated_at, déjalo en true (o borra la línea de timestamps = false)
    public $timestamps = true; 

    protected $fillable = [
        'id_bicicleta',
        'id_estacion',
        'user_id',
        'tipo_novedad',
        'descripcion'
    ];

    public function bicicleta()
    {
        return $this->belongsTo(Bicicleta::class, 'id_bicicleta', 'id');
    }

    public function estacion()
    {
        return $this->belongsTo(Estacion::class, 'id_estacion', 'id_estacion');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}