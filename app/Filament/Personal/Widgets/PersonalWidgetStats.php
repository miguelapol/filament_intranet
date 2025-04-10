<?php

namespace App\Filament\Personal\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
//model Holidays
use App\Models\Holiday;
use App\Models\Timesheet;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

//Auth




class PersonalWidgetStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Pending Holidays', $this->getPendingHolidays(auth()->user())),
            Stat::make('Approved Holidays', $this->getApprovedHolidays(auth()->user())),
            Stat::make('Total', $this->getTotalWorkHours(auth()->user())),
        ];
    }
    protected function getPendingHolidays(User $user)
    {
        $pending_holidays = Holiday::where('type', 'pending')->where('user_id', $user->id)->count();
        return $pending_holidays;
    }
    protected function getApprovedHolidays(User $user){
        $approved_holidays = Holiday::where('type', 'approved')->where('user_id', $user->id)->count();
        return $approved_holidays;
    }
    protected function getTotalWorkHours(User $user){
        //solo agregar que day_in y day:out no sean nulos
        $timesheets = Timesheet::where('type', 'work')->whereNotNull('day_out')
                                                      ->whereNotNull('day_in')
                                                      ->where('user_id', $user->id)->get();
        $totalSeconds = 0;
        //para hacer convertido todo el dia en una unidad comun en este caso en segundos
        foreach ($timesheets as $timesheet) {
            $dayIn = strtotime($timesheet->day_in);
            $dayOut = strtotime($timesheet->day_out);
            $totalSeconds += ($dayOut - $dayIn);
        }

        $totalHours = $totalSeconds / 3600;
        $totalMinutes = ($totalSeconds % 3600) / 60;
        $seconds = $totalSeconds % 60;
        return sprintf('%d:%02d:%02d', $totalHours, $totalMinutes, $seconds);
    }
}
