<?php

namespace App\Filament\Widgets;

use App\Models\Actividad;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class UltimasActividadesWidget extends BaseWidget
{
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Actividad::query()
                    ->with('campana')
                    ->latest()
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('titulo')
                    ->label('Título')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('campana.nombre')
                    ->label('Campaña')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('fecha_actividad')
                    ->label('Fecha')
                    ->date('d/m/Y')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('hora_actividad')
                    ->label('Hora')
                    ->time('H:i'),
                
                Tables\Columns\TextColumn::make('lugar')
                    ->label('Lugar')
                    ->searchable()
                    ->limit(30),
                
                Tables\Columns\TextColumn::make('estado')
                    ->badge()
                    ->sortable(),
            ])
            ->heading('Últimas 10 Actividades');
    }
}
