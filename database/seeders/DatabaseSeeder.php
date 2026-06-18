<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
   public function run(): void
    {
        $this->call(ProyectoSeeder::class);

        \App\Models\User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // 🌟 NUEVO: Semillas estratégicas para las estaciones de Jardín Bike
        $estaciones = [
            [
                'codigo' => 'EST-PARQUE',
                'nombre' => 'Estación Parque Principal',
                'direccion' => 'Calle 10 (Frente a la Basílica)',
                'coordenadas' => '5.59833,-75.81922',
                'capacidad' => 15,
                'estado' => 'Activa'
            ],
            [
                'codigo' => 'EST-TERMINAL',
                'nombre' => 'Estación Terminal de Transportes',
                'direccion' => 'Carrera 5 # 7-20',
                'coordenadas' => '5.60081,-75.81845',
                'capacidad' => 12,
                'estado' => 'Activa'
            ],
            [
                'codigo' => 'EST-GARRUCHA',
                'nombre' => 'Estación El Cable / La Garrucha',
                'direccion' => 'Sector La Herrera',
                'coordenadas' => '5.59542,-75.81423',
                'capacidad' => 10,
                'estado' => 'Activa'
            ],
            [
                'codigo' => 'EST-ESPLENDOR',
                'nombre' => 'Estación Salida Cueva del Esplendor',
                'direccion' => 'Salida Norte Urbana',
                'coordenadas' => '5.60250,-75.82350',
                'capacidad' => 10,
                'estado' => 'Activa'
            ],
            [
                'codigo' => 'EST-HOSPITAL',
                'nombre' => 'Estación Hospital / Unidad Deportiva',
                'direccion' => 'Carrera 2 (Zona Sur)',
                'coordenadas' => '5.59410,-75.82390',
                'capacidad' => 15,
                'estado' => 'Activa'
            ]
        ];

        foreach ($estaciones as $estacion) {
            // Usamos updateOrCreate para que si lo corres de nuevo no duplique registros
            \App\Models\Estacion::updateOrCreate(['codigo' => $estacion['codigo']], $estacion);
        }
    }
}
