<?php

namespace App\Filament\Widgets;

use App\Enums\EstadoActividad;
use App\Models\Actividad;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ActividadesStatsWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $total = Actividad::count();
        $programadas = Actividad::where('estado', EstadoActividad::PROGRAMADA)->count();
        $canceladas = Actividad::where('estado', EstadoActividad::CANCELADA)->count();
        $completas = Actividad::where('estado', EstadoActividad::COMPLETA)->count();

        return [
            Stat::make('Total Actividades', $total)
                ->description('Todas las actividades registradas')
                ->descriptionIcon('heroicon-o-calendar')
                ->color('primary'),
            
            Stat::make('Actividades Programadas', $programadas)
                ->description('Pendientes de realizar')
                ->descriptionIcon('heroicon-o-clock')
                ->color('warning'),
            
            Stat::make('Actividades Completas', $completas)
                ->description('Realizadas exitosamente')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),
            
            Stat::make('Actividades Canceladas', $canceladas)
                ->description('Canceladas o suspendidas')
                ->descriptionIcon('heroicon-o-x-circle')
                ->color('danger'),
        ];
    }
}
