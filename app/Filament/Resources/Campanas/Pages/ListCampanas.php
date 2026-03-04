<?php

namespace App\Filament\Resources\Campanas\Pages;

use App\Filament\Resources\Campanas\CampanaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCampanas extends ListRecords
{
    protected static string $resource = CampanaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
