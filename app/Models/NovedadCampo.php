<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NovedadCampo extends Model
{
    protected $table = 'novedad_campos';
    protected $primaryKey = 'id_novedad';
    public $timestamps = false;

    protected $fillable = [
        'bicicleta_id',
        'estacion_id',
        'user_id',
        'descripcion',
        'tipo_novedad'
    ];

    public function bicicleta()
    {
        return $this->belongsTo(Bicicleta::class, 'bicicleta_id', 'id_bicicleta');
    }

    public function estacion()
    {
        return $this->belongsTo(Estacion::class, 'estacion_id', 'id_estacion');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}