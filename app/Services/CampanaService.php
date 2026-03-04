<?php

namespace App\Services;

use App\Enums\EstadoCampana;
use App\Enums\EstadoConfirmacion;
use App\Models\Campana;
use App\Models\Cliente;
use App\Models\MensajeLog;
use Illuminate\Support\Facades\DB;

class CampanaService
{
    public function enviarCampana(Campana $campana): array
    {
        DB::beginTransaction();

        try {
            $actividades = $campana->actividades()->with('clientes')->get();
            $resultados = [
                'total_clientes' => 0,
                'mensajes_directos' => 0,
                'mensajes_consolidados' => 0,
                'errores' => 0,
            ];

            // Agrupar actividades por cliente
            $clientesActividades = [];
            foreach ($actividades as $actividad) {
                foreach ($actividad->clientes as $cliente) {
                    if (!isset($clientesActividades[$cliente->id])) {
                        $clientesActividades[$cliente->id] = [
                            'cliente' => $cliente,
                            'actividades' => [],
                        ];
                    }
                    $clientesActividades[$cliente->id]['actividades'][] = $actividad;
                }
            }

            $resultados['total_clientes'] = count($clientesActividades);

            // Enviar mensajes según la cantidad de actividades
            foreach ($clientesActividades as $data) {
                $cliente = $data['cliente'];
                $actividadesCliente = $data['actividades'];

                try {
                    if (count($actividadesCliente) === 1) {
                        // Mensaje directo con enlace a confirmación individual
                        $this->enviarMensajeDirecto($cliente, $actividadesCliente[0]);
                        $resultados['mensajes_directos']++;
                    } else {
                        // Mensaje con URL consolidada
                        $this->enviarMensajeConsolidado($cliente, $actividadesCliente);
                        $resultados['mensajes_consolidados']++;
                    }
                } catch (\Exception $e) {
                    $resultados['errores']++;
                    $this->registrarError($cliente, $e->getMessage());
                }
            }

            // Actualizar estado de la campaña
            $campana->update(['estado' => EstadoCampana::ENVIADA]);

            DB::commit();

            return $resultados;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function enviarMensajeDirecto(Cliente $cliente, $actividad): void
    {
        $pivot = DB::table('actividad_cliente')
            ->where('actividad_id', $actividad->id)
            ->where('cliente_id', $cliente->id)
            ->first();

        // Usar el token en el cuerpo, no en la URL
        $url = url("/confirmaciones/" . hash('sha256', $cliente->telefono_whatsapp));
        
        $mensaje = "Hola {$cliente->nombre}! Te invitamos a: {$actividad->titulo}\n";
        $mensaje .= "Fecha: {$actividad->fecha_actividad->format('d/m/Y')} a las {$actividad->hora_actividad}\n";
        $mensaje .= "Lugar: {$actividad->lugar}\n";
        $mensaje .= "Confirma tu asistencia aquí: {$url}";

        // Aquí iría la integración con la API de WhatsApp
        // Por ahora solo registramos el log
        MensajeLog::create([
            'cliente_id' => $cliente->id,
            'actividad_id' => $actividad->id,
            'tipo' => 'whatsapp_directo',
            'contenido' => $mensaje,
            'respuesta_api' => json_encode(['simulado' => true]),
            'estado_envio' => 'enviado',
        ]);
    }

    private function enviarMensajeConsolidado(Cliente $cliente, array $actividades): void
    {
        $hashTelefono = hash('sha256', $cliente->telefono_whatsapp);
        $url = url("/confirmaciones/{$hashTelefono}");
        
        $mensaje = "Hola {$cliente->nombre}! Tienes " . count($actividades) . " actividades pendientes de confirmar.\n";
        $mensaje .= "Revisa y confirma tu asistencia aquí: {$url}";

        // Aquí iría la integración con la API de WhatsApp
        MensajeLog::create([
            'cliente_id' => $cliente->id,
            'actividad_id' => null,
            'tipo' => 'whatsapp_consolidado',
            'contenido' => $mensaje,
            'respuesta_api' => json_encode(['simulado' => true]),
            'estado_envio' => 'enviado',
        ]);
    }

    private function registrarError(Cliente $cliente, string $error): void
    {
        MensajeLog::create([
            'cliente_id' => $cliente->id,
            'actividad_id' => null,
            'tipo' => 'error',
            'contenido' => $error,
            'respuesta_api' => null,
            'estado_envio' => 'fallido',
        ]);
    }
}
