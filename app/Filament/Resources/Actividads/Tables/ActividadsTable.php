<?php

namespace App\Filament\Resources\Actividads\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ActividadsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('campana.nombre')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('titulo')
                    ->searchable(),
                TextColumn::make('fecha_actividad')
                    ->date()
                    ->sortable(),
                TextColumn::make('hora_actividad')
                    ->time()
                    ->sortable(),
                TextColumn::make('lugar')
                    ->searchable(),
                TextColumn::make('cupo_maximo')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('estado')
                    ->badge()
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
