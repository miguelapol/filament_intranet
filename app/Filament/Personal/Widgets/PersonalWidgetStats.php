<?php

namespace App\Filament\Personal\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
//model Holidays
use App\Models\Holiday;
use App\Models\Timesheet;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

//Auth




class PersonalWidgetStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Pending Holidays', $this->getPendingHolidays(auth()->user())),
            Stat::make('Approved Holidays', $this->getApprovedHolidays(auth()->user())),
            Stat::make('Total work', $this->getTotalWorkHours(auth()->user())),
            Stat::make('Total pause', $this->getTotalWorkPause(auth()->user())),
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
        $timesheets = Timesheet::where('user_id', $user->id)->where('type','work')->whereDate('created_at', Carbon::today())->get();
        $totalSeconds = 0;
        //para hacer convertido todo el dia en una unidad comun en este caso en segundos
        foreach ($timesheets as $timesheet) {
            $dayIn = Carbon::parse($timesheet->day_in);
            //debugar que valor hay usando debuger

            $dayOut = Carbon::parse($timesheet->day_out);
            $totalDuration=$dayOut->diffInSeconds($dayIn);
            $totalSeconds=$totalSeconds+$totalDuration;
        }
       $tiempoFormato=gmdate("H:i:s", $totalSeconds);
        return $tiempoFormato;
    }
    //obtener ahora el type pending
    protected function getTotalWorkPause(User $user)
    {
         //solo agregar que day_in y day:out no sean nulos
         //este codigo solo contara las horas hechas al dia mostrara las horas de hoy
         $timesheets = Timesheet::where('user_id', $user->id)->where('type','pause')->whereDate('created_at', Carbon::today())->get();
         $totalSeconds = 0;
         //para hacer convertido todo el dia en una unidad comun en este caso en segundos
         foreach ($timesheets as $timesheet) {
             $dayIn = Carbon::parse($timesheet->day_in);
             //debugar que valor hay usando debuger

             $dayOut = Carbon::parse($timesheet->day_out);
             $totalDuration=$dayOut->diffInSeconds($dayIn);
             $totalSeconds=$totalSeconds+$totalDuration;
         }
        $tiempoFormato=gmdate("H:i:s", $totalSeconds);
         return $tiempoFormato;
    }
}
