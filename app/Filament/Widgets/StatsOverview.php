<?php

namespace App\Filament\Widgets;
//ususarios del model User
use App\Models\User;
use App\Models\Holiday;
use App\Models\Timesheet;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        //aqui cada cierto tiempo hace un update esta widget
        //recuerda livewire
        $totalUsers=User::count();
        //vacaciones en pendiente
        $totalHolidaysPendientes=Holiday::where('type','pending')->count();
        //en horas timesheets
        $totalTimesheets=Timesheet::count();
        return [
            Stat::make('Employees', $totalUsers)
            ->description('32k increase')
            ->descriptionIcon('heroicon-m-arrow-trending-up'),
            Stat::make('Pending Holidays', $totalHolidaysPendientes)
            //
            ->description('Pendientes')
            ->descriptionIcon('heroicon-m-calendar-date-range'),

            //en horas  $totalTimesheets
            Stat::make('Hours', $totalTimesheets)
            ->description('Total horas')
            ->descriptionIcon('heroicon-m-clock'),
        ];
    }
}
