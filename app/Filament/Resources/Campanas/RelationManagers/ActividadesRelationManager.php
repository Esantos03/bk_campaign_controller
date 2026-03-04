<?php

namespace App\Filament\Resources\Campanas\RelationManagers;

use App\Enums\EstadoActividad;
use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ActividadesRelationManager extends RelationManager
{
    protected static string $relationship = 'actividades';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('titulo')
                    ->required()
                    ->maxLength(255),
                Textarea::make('descripcion')
                    ->columnSpanFull(),
                DatePicker::make('fecha_actividad')
                    ->required(),
                TimePicker::make('hora_actividad')
                    ->required(),
                TextInput::make('lugar')
                    ->required()
                    ->maxLength(255),
                TextInput::make('cupo_maximo')
                    ->numeric()
                    ->minValue(1),
                Select::make('estado')
                    ->options(EstadoActividad::class)
                    ->default('programada')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('titulo')
            ->columns([
                TextColumn::make('titulo')
                    ->searchable(),
                TextColumn::make('fecha_actividad')
                    ->date()
                    ->sortable(),
                TextColumn::make('hora_actividad')
                    ->time('H:i'),
                TextColumn::make('lugar')
                    ->searchable(),
                TextColumn::make('cupo_maximo')
                    ->numeric(),
                TextColumn::make('estado')
                    ->badge(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
