<?php

namespace App\Models;

use App\Enums\EstadoCliente;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cliente extends Model
{
    protected $fillable = [
        'nombre',
        'apellido',
        'telefono_whatsapp',
        'email',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'estado' => EstadoCliente::class,
        ];
    }

    public function actividades(): BelongsToMany
    {
        return $this->belongsToMany(Actividad::class, 'actividad_cliente')
            ->using(\App\Models\ActividadCliente::class)
            ->withPivot('estado_confirmacion', 'token_unico', 'fecha_confirmacion')
            ->withTimestamps();
    }

    public function mensajesLogs(): HasMany
    {
        return $this->hasMany(MensajeLog::class);
    }
}
