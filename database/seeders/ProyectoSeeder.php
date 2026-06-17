<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProyectoSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Insertar Estaciones Base (con coordenadas simuladas de Jardín)
        DB::table('estaciones')->insert([
            [
                'codigo' => 'EST-PARQUE',
                'nombre' => 'Parque Principal',
                'direccion' => 'Calle 10 (Frente a la Iglesia)',
                'coordenadas' => '5.5983,-75.8192',
                'capacidad' => 15,
                'energia_disp' => 100,
                'estado' => 'Activa',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'codigo' => 'EST-HERRERA',
                'nombre' => 'Camino de la Herrera',
                'direccion' => 'Inicio del Sendero Peatonal',
                'coordenadas' => '5.5965,-75.8230',
                'capacidad' => 10,
                'energia_disp' => 85,
                'estado' => 'Activa',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'codigo' => 'EST-GARRUCHA',
                'nombre' => 'Sector La Garrucha',
                'direccion' => 'Estación del Cable',
                'coordenadas' => '5.6021,-75.8155',
                'capacidad' => 8,
                'energia_disp' => 90,
                'estado' => 'Mantenimiento',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // 2. Insertar Bicicletas vinculadas obligatoriamente a una estación (RN-04)
        DB::table('bicicletas')->insert([
            [
                'estacion_act' => 1, // Vinculada al Parque Principal
                'codigo_qr' => 'QR-BICI-045',
                'modelo' => 'E-Bike Urban Pro',
                'num_serie' => 'SN-EB987654321',
                'nivel_bateria' => 15,
                'estado' => 'Disponible',
                'kilometraje' => 120.50,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'estacion_act' => 1, // Vinculada al Parque Principal
                'codigo_qr' => 'QR-BICI-012',
                'modelo' => 'E-Bike Urban Pro',
                'num_serie' => 'SN-EB123456789',
                'nivel_bateria' => 12, // Estado crítico para cargar en el panel
                'estado' => 'Crítico - Cargar',
                'kilometraje' => 345.20,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'estacion_act' => 2, // Vinculada a Camino de la Herrera
                'codigo_qr' => 'QR-BICI-088',
                'modelo' => 'E-Bike Mountain',
                'num_serie' => 'SN-MT555444333',
                'nivel_bateria' => 80,
                'estado' => 'En uso',
                'kilometraje' => 45.10,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}