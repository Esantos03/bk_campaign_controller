<?php

namespace App\Models;

use App\Enums\EstadoActividad;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Actividad extends Model
{
    protected $table = 'actividades';

    protected $fillable = [
        'campana_id',
        'titulo',
        'descripcion',
        'fecha_actividad',
        'hora_actividad',
        'lugar',
        'cupo_maximo',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'estado' => EstadoActividad::class,
            'fecha_actividad' => 'date',
            'hora_actividad' => 'datetime:H:i',
        ];
    }

    public function campana(): BelongsTo
    {
        return $this->belongsTo(Campana::class);
    }

    public function clientes(): BelongsToMany
    {
        return $this->belongsToMany(Cliente::class, 'actividad_cliente')
            ->using(\App\Models\ActividadCliente::class)
            ->withPivot('estado_confirmacion', 'token_unico', 'fecha_confirmacion')
            ->withTimestamps();
    }

    public function mensajesLogs(): HasMany
    {
        return $this->hasMany(MensajeLog::class);
    }
}
