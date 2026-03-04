<?php

namespace App\Services;

use App\Enums\EstadoActividad;
use App\Enums\EstadoConfirmacion;
use App\Models\Actividad;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ConfirmacionService
{
    public function confirmar(string $token, bool $confirmar): array
    {
        return DB::transaction(function () use ($token, $confirmar) {
            $pivot = DB::table('actividad_cliente')
                ->where('token_unico', $token)
                ->lockForUpdate()
                ->first();

            if (!$pivot) {
                throw new \Exception('Token inválido o no encontrado');
            }

            // R1: Verificar que no esté ya confirmado/rechazado
            if ($pivot->estado_confirmacion !== EstadoConfirmacion::PENDIENTE->value) {
                throw new \Exception("Esta actividad ya fue {$pivot->estado_confirmacion}");
            }

            $actividad = Actividad::findOrFail($pivot->actividad_id);

            // R2: Verificar cupo máximo si es confirmación
            if ($confirmar && $actividad->cupo_maximo) {
                $confirmados = DB::table('actividad_cliente')
                    ->where('actividad_id', $actividad->id)
                    ->where('estado_confirmacion', EstadoConfirmacion::CONFIRMADO->value)
                    ->count();

                if ($confirmados >= $actividad->cupo_maximo) {
                    throw new \Exception('Lo sentimos, el cupo máximo ha sido alcanzado');
                }
            }

            // Actualizar estado
            $nuevoEstado = $confirmar 
                ? EstadoConfirmacion::CONFIRMADO->value 
                : EstadoConfirmacion::RECHAZADO->value;

            DB::table('actividad_cliente')
                ->where('id', $pivot->id)
                ->update([
                    'estado_confirmacion' => $nuevoEstado,
                    'fecha_confirmacion' => now(),
                    'updated_at' => now(),
                ]);

            // Verificar si se alcanzó el cupo máximo y marcar actividad como completa
            if ($confirmar && $actividad->cupo_maximo) {
                $confirmados = DB::table('actividad_cliente')
                    ->where('actividad_id', $actividad->id)
                    ->where('estado_confirmacion', EstadoConfirmacion::CONFIRMADO->value)
                    ->count();

                if ($confirmados >= $actividad->cupo_maximo) {
                    $actividad->update(['estado' => EstadoActividad::COMPLETA]);
                    
                    Log::info('Actividad completada por cupo máximo', [
                        'actividad_id' => $actividad->id,
                        'cupo_maximo' => $actividad->cupo_maximo,
                    ]);
                }
            }

            Log::info('Confirmación procesada', [
                'token' => $token,
                'accion' => $confirmar ? 'confirmar' : 'rechazar',
                'actividad_id' => $actividad->id,
                'cliente_id' => $pivot->cliente_id,
            ]);

            return [
                'success' => true,
                'message' => $confirmar 
                    ? 'Asistencia confirmada exitosamente' 
                    : 'Asistencia rechazada',
                'actividad' => [
                    'id' => $actividad->id,
                    'titulo' => $actividad->titulo,
                    'estado' => $actividad->estado->value,
                ],
            ];
        });
    }

    public function obtenerActividadesPendientes(string $telefono): array
    {
        $cliente = DB::table('clientes')
            ->where('telefono_whatsapp', $telefono)
            ->first();

        if (!$cliente) {
            throw new \Exception('Cliente no encontrado');
        }

        $actividades = DB::table('actividades')
            ->join('actividad_cliente', 'actividades.id', '=', 'actividad_cliente.actividad_id')
            ->join('campanas', 'actividades.campana_id', '=', 'campanas.id')
            ->where('actividad_cliente.cliente_id', $cliente->id)
            ->where('actividad_cliente.estado_confirmacion', EstadoConfirmacion::PENDIENTE->value)
            ->select([
                'actividades.id',
                'actividades.titulo',
                'actividades.descripcion',
                'actividades.fecha_actividad',
                'actividades.hora_actividad',
                'actividades.lugar',
                'actividades.cupo_maximo',
                'campanas.nombre as campana',
                'actividad_cliente.token_unico as token',
            ])
            ->get();

        Log::info('Actividades pendientes consultadas', [
            'telefono' => $telefono,
            'cliente_id' => $cliente->id,
            'cantidad' => $actividades->count(),
        ]);

        return [
            'success' => true,
            'cliente' => [
                'nombre' => $cliente->nombre,
                'apellido' => $cliente->apellido,
            ],
            'actividades' => $actividades->map(function ($act) {
                return [
                    'id' => $act->id,
                    'titulo' => $act->titulo,
                    'descripcion' => $act->descripcion,
                    'fecha_actividad' => $act->fecha_actividad,
                    'hora_actividad' => $act->hora_actividad,
                    'lugar' => $act->lugar,
                    'cupo_maximo' => $act->cupo_maximo,
                    'campana' => $act->campana,
                    'token' => $act->token,
                ];
            })->toArray(),
        ];
    }
}
