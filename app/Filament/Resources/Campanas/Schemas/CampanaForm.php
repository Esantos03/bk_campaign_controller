<?php

namespace App\Filament\Resources\Campanas\Schemas;

use App\Enums\EstadoCampana;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Get;
use Filament\Schemas\Schema;

class CampanaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre')
                    ->required()
                    ->maxLength(255)
                    ->minLength(3)
                    ->unique(ignoreRecord: true),
                Textarea::make('descripcion')
                    ->columnSpanFull()
                    ->maxLength(1000)
                    ->rows(3),
                DatePicker::make('fecha_inicio')
                    ->required()
                    ->native(false)
                    ->displayFormat('d/m/Y')
                    ->minDate(now()->subDays(1))
                    ->afterStateUpdated(fn ($state, callable $set) => $set('fecha_fin', null)),
                DatePicker::make('fecha_fin')
                    ->required()
                    ->native(false)
                    ->displayFormat('d/m/Y')
                    ->minDate(fn (Get $get) => $get('fecha_inicio') ?: now())
                    ->afterOrEqual('fecha_inicio')
                    ->helperText('Debe ser posterior a la fecha de inicio'),
                Select::make('estado')
                    ->options(EstadoCampana::class)
                    ->default('borrador')
                    ->required()
                    ->disabled(fn ($record) => $record?->estado === EstadoCampana::ENVIADA || $record?->estado === EstadoCampana::FINALIZADA),
            ]);
    }
}
