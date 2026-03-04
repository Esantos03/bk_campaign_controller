<?php

namespace App\Models;

use App\Enums\EstadoCampana;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Campana extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
        'fecha_inicio',
        'fecha_fin',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'estado' => EstadoCampana::class,
            'fecha_inicio' => 'date',
            'fecha_fin' => 'date',
        ];
    }

    public function actividades(): HasMany
    {
        return $this->hasMany(Actividad::class);
    }
}
