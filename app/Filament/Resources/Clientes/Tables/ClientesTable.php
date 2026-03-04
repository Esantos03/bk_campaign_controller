<?php

namespace App\Filament\Resources\Clientes\Tables;

use App\Enums\EstadoCliente;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ClientesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nombre')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('apellido')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('telefono_whatsapp')
                    ->searchable()
                    ->label('Teléfono'),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('profesion')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Arquitecto' => 'info',
                        'Programador' => 'success',
                        'Ingeniero' => 'warning',
                        'Diseñador' => 'danger',
                        'Médico' => 'primary',
                        default => 'gray',
                    })
                    ->placeholder('Sin especificar'),
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
                SelectFilter::make('estado')
                    ->options(EstadoCliente::class),
                SelectFilter::make('profesion')
                    ->options([
                        'Arquitecto' => 'Arquitecto',
                        'Programador' => 'Programador',
                        'Ingeniero' => 'Ingeniero',
                        'Diseñador' => 'Diseñador',
                        'Médico' => 'Médico',
                        'Abogado' => 'Abogado',
                        'Contador' => 'Contador',
                        'Profesor' => 'Profesor',
                        'Enfermero' => 'Enfermero',
                        'Administrador' => 'Administrador',
                        'Vendedor' => 'Vendedor',
                        'Empresario' => 'Empresario',
                        'Estudiante' => 'Estudiante',
                        'Otro' => 'Otro',
                    ])
                    ->multiple()
                    ->searchable(),
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
