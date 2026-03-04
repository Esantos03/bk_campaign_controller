<?php

namespace Database\Seeders;

use App\Enums\EstadoActividad;
use App\Enums\EstadoCampana;
use App\Enums\EstadoCliente;
use App\Models\Actividad;
use App\Models\Campana;
use App\Models\Cliente;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CampanaSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            // Crear clientes de prueba
            $clientes = [
                Cliente::create([
                    'nombre' => 'Juan',
                    'apellido' => 'Pérez',
                    'telefono_whatsapp' => '1234567890',
                    'email' => 'juan@example.com',
                    'profesion' => 'Arquitecto',
                    'estado' => EstadoCliente::ACTIVO,
                ]),
                Cliente::create([
                    'nombre' => 'María',
                    'apellido' => 'González',
                    'telefono_whatsapp' => '0987654321',
                    'email' => 'maria@example.com',
                    'profesion' => 'Programador',
                    'estado' => EstadoCliente::ACTIVO,
                ]),
                Cliente::create([
                    'nombre' => 'Carlos',
                    'apellido' => 'Rodríguez',
                    'telefono_whatsapp' => '5555555555',
                    'email' => 'carlos@example.com',
                    'profesion' => 'Ingeniero',
                    'estado' => EstadoCliente::ACTIVO,
                ]),
            ];

            // Crear campaña de prueba
            $campana = Campana::create([
                'nombre' => 'Campaña de Verano 2026',
                'descripcion' => 'Actividades recreativas para la temporada de verano',
                'fecha_inicio' => now(),
                'fecha_fin' => now()->addMonths(3),
                'estado' => EstadoCampana::BORRADOR,
            ]);

            // Crear actividades
            $actividad1 = Actividad::create([
                'campana_id' => $campana->id,
                'titulo' => 'Taller de Yoga',
                'descripcion' => 'Sesión de yoga para principiantes',
                'fecha_actividad' => now()->addDays(7),
                'hora_actividad' => '09:00:00',
                'lugar' => 'Parque Central',
                'cupo_maximo' => 20,
                'estado' => EstadoActividad::PROGRAMADA,
            ]);

            $actividad2 = Actividad::create([
                'campana_id' => $campana->id,
                'titulo' => 'Clase de Cocina',
                'descripcion' => 'Aprende a cocinar platos saludables',
                'fecha_actividad' => now()->addDays(10),
                'hora_actividad' => '15:00:00',
                'lugar' => 'Centro Comunitario',
                'cupo_maximo' => 15,
                'estado' => EstadoActividad::PROGRAMADA,
            ]);

            $actividad3 = Actividad::create([
                'campana_id' => $campana->id,
                'titulo' => 'Caminata Ecológica',
                'descripcion' => 'Recorrido por senderos naturales',
                'fecha_actividad' => now()->addDays(14),
                'hora_actividad' => '07:00:00',
                'lugar' => 'Reserva Natural',
                'cupo_maximo' => null,
                'estado' => EstadoActividad::PROGRAMADA,
            ]);

            // Asignar clientes a actividades (el modelo pivot generará tokens automáticamente)
            foreach ($clientes as $cliente) {
                // Cliente 1: 1 actividad
                if ($cliente->id === 1) {
                    $actividad1->clientes()->attach($cliente->id);
                }
                
                // Cliente 2: 2 actividades
                if ($cliente->id === 2) {
                    $actividad1->clientes()->attach($cliente->id);
                    $actividad2->clientes()->attach($cliente->id);
                }
                
                // Cliente 3: 3 actividades
                if ($cliente->id === 3) {
                    $actividad1->clientes()->attach($cliente->id);
                    $actividad2->clientes()->attach($cliente->id);
                    $actividad3->clientes()->attach($cliente->id);
                }
            }

            $this->command->info('Datos de prueba creados exitosamente!');
        });
    }
}
