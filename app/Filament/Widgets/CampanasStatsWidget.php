<?php

namespace App\Filament\Widgets;

use App\Enums\EstadoCampana;
use App\Models\Campana;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CampanasStatsWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $total = Campana::count();
        $borradores = Campana::where('estado', EstadoCampana::BORRADOR)->count();
        $enviadas = Campana::where('estado', EstadoCampana::ENVIADA)->count();
        $finalizadas = Campana::where('estado', EstadoCampana::FINALIZADA)->count();

        return [
            Stat::make('Total Campañas', $total)
                ->description('Todas las campañas registradas')
                ->descriptionIcon('heroicon-o-megaphone')
                ->color('primary'),
            
            Stat::make('Campañas en Borrador', $borradores)
                ->description('Pendientes de enviar')
                ->descriptionIcon('heroicon-o-document-text')
                ->color('gray'),
            
            Stat::make('Campañas Enviadas', $enviadas)
                ->description('En proceso activo')
                ->descriptionIcon('heroicon-o-paper-airplane')
                ->color('info'),
            
            Stat::make('Campañas Finalizadas', $finalizadas)
                ->description('Completadas')
                ->descriptionIcon('heroicon-o-check-badge')
                ->color('success'),
        ];
    }
}
