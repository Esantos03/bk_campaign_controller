<?php

namespace App\Filament\Resources\Campanas\Pages;

use App\Enums\EstadoCampana;
use App\Filament\Resources\Campanas\CampanaResource;
use App\Services\CampanaService;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditCampana extends EditRecord
{
    protected static string $resource = CampanaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('enviar_campana')
                ->label('Enviar Campaña')
                ->icon('heroicon-o-paper-airplane')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Enviar Campaña')
                ->modalDescription('¿Estás seguro de que deseas enviar esta campaña? Se enviarán mensajes a todos los clientes asignados.')
                ->visible(fn () => $this->record->estado === EstadoCampana::BORRADOR)
                ->action(function (CampanaService $service) {
                    try {
                        $resultados = $service->enviarCampana($this->record);
                        
                        Notification::make()
                            ->title('Campaña enviada exitosamente')
                            ->body("Total clientes: {$resultados['total_clientes']}, Mensajes directos: {$resultados['mensajes_directos']}, Mensajes consolidados: {$resultados['mensajes_consolidados']}, Errores: {$resultados['errores']}")
                            ->success()
                            ->send();
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Error al enviar campaña')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
            DeleteAction::make(),
        ];
    }
}

