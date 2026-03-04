<?php

namespace App\Filament\Widgets;

use App\Enums\EstadoCliente;
use App\Models\Cliente;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ClientesStatsWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $total = Cliente::count();
        $activos = Cliente::where('estado', EstadoCliente::ACTIVO)->count();
        $inactivos = Cliente::where('estado', EstadoCliente::INACTIVO)->count();
        
        // Clientes con profesión definida
        $conProfesion = Cliente::whereNotNull('profesion')->count();

        return [
            Stat::make('Total Clientes', $total)
                ->description('Todos los clientes registrados')
                ->descriptionIcon('heroicon-o-users')
                ->color('primary'),
            
            Stat::make('Clientes Activos', $activos)
                ->description('Disponibles para campañas')
                ->descriptionIcon('heroicon-o-user-circle')
                ->color('success'),
            
            Stat::make('Clientes Inactivos', $inactivos)
                ->description('No disponibles')
                ->descriptionIcon('heroicon-o-user-minus')
                ->color('danger'),
            
            Stat::make('Con Profesión', $conProfesion)
                ->description('Clientes con profesión definida')
                ->descriptionIcon('heroicon-o-briefcase')
                ->color('info'),
        ];
    }
}
