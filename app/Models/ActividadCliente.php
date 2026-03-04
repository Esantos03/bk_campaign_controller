<?php

namespace App\Models;

use App\Enums\EstadoConfirmacion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ActividadCliente extends Model
{
    protected $table = 'actividad_cliente';

    protected $fillable = [
        'actividad_id',
        'cliente_id',
        'estado_confirmacion',
        'token_unico',
        'fecha_confirmacion',
    ];

    protected function casts(): array
    {
        return [
            'estado_confirmacion' => EstadoConfirmacion::class,
            'fecha_confirmacion' => 'datetime',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->token_unico)) {
                $model->token_unico = Str::uuid();
            }
            if (empty($model->estado_confirmacion)) {
                $model->estado_confirmacion = EstadoConfirmacion::PENDIENTE;
            }
        });
    }

    public function actividad(): BelongsTo
    {
        return $this->belongsTo(Actividad::class);
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function scopePendientes($query)
    {
        return $query->where('estado_confirmacion', EstadoConfirmacion::PENDIENTE);
    }

    public function scopeConfirmados($query)
    {
        return $query->where('estado_confirmacion', EstadoConfirmacion::CONFIRMADO);
    }

    public function scopeRechazados($query)
    {
        return $query->where('estado_confirmacion', EstadoConfirmacion::RECHAZADO);
    }
}
