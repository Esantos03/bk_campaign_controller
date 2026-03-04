<?php

namespace App\Filament\Resources\Campanas;

use App\Filament\Resources\Campanas\Pages\CreateCampana;
use App\Filament\Resources\Campanas\Pages\EditCampana;
use App\Filament\Resources\Campanas\Pages\ListCampanas;
use App\Filament\Resources\Campanas\Schemas\CampanaForm;
use App\Filament\Resources\Campanas\Tables\CampanasTable;
use App\Models\Campana;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CampanaResource extends Resource
{
    protected static ?string $model = Campana::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return CampanaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CampanasTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            \App\Filament\Resources\Campanas\RelationManagers\ActividadesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCampanas::route('/'),
            'create' => CreateCampana::route('/create'),
            'edit' => EditCampana::route('/{record}/edit'),
        ];
    }
}
