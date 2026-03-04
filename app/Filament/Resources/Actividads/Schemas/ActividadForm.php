<?php

namespace App\Filament\Resources\Actividads\Schemas;

use App\Enums\EstadoActividad;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Schema;

class ActividadForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('campana_id')
                    ->relationship('campana', 'nombre')
                    ->required()
                    ->searchable()
                    ->preload(),
                TextInput::make('titulo')
                    ->required()
                    ->maxLength(255)
                    ->minLength(3),
                Textarea::make('descripcion')
                    ->columnSpanFull()
                    ->maxLength(1000)
                    ->rows(3),
                DatePicker::make('fecha_actividad')
                    ->required()
                    ->native(false)
                    ->displayFormat('d/m/Y')
                    ->minDate(now()->subDays(1)),
                TimePicker::make('hora_actividad')
                    ->required()
                    ->native(false)
                    ->seconds(false),
                TextInput::make('lugar')
                    ->required()
                    ->maxLength(255)
                    ->minLength(3),
                TextInput::make('cupo_maximo')
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(1000)
                    ->helperText('Dejar vacío para cupo ilimitado'),
                Select::make('estado')
                    ->options(EstadoActividad::class)
                    ->default('programada')
                    ->required(),
            ]);
    }
}
