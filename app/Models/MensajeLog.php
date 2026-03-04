<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MensajeLog extends Model
{
    protected $table = 'mensajes_logs';

    protected $fillable = [
        'cliente_id',
        'actividad_id',
        'tipo',
        'contenido',
        'respuesta_api',
        'estado_envio',
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function actividad(): BelongsTo
    {
        return $this->belongsTo(Actividad::class);
    }
}
