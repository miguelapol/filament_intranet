<?php

namespace App\Filament\Personal\Resources\TimesheetResource\Pages;

use App\Filament\Personal\Resources\TimesheetResource;
use Filament\Actions;
//CARBON
use Carbon\carbon;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Support\Exceptions\Halt;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Hidden;
use Filament\Actions\Action;

//model Timesheet
use App\Models\Timesheet;
//Calendar
use App\Models\Calendar;



class ListTimesheets extends ListRecords

{
    protected static string $resource = TimesheetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('in Work')
                ->label('Entrar a trabajar')->color('success')
                ->action(function () {
                    $user=Auth::user();
                    //verificar que day_out este null entonces salta notificacion
                    $openWorkSession=Timesheet::where('user_id', $user->id)
                        ->where('type', 'work')
                        ->where('day_out', null)
                        ->latest()
                        ->first();
                        if ($openWorkSession) {
                            Notification::make()
                                ->title('Ya tienes una sesión de trabajo activa')
                                ->danger()
                                ->send();
                            return;
                        }
                        //obtenemos el ultimo registro agregado de calendar
                        $calendarUltimate=Calendar::latest()->first();

                        Timesheet::create([
                            'user_id' => $user->id,
                            'calendar_id' =>$calendarUltimate->id,
                            'type' => 'work',
                            'day_in' => Carbon::now(),
                            'day_out' => null,
                        ]);
                          Notification::make()
                        ->title('¡Sesión de trabajo iniciada!')
                        ->success()
                        ->send();
                }),
            Action::make('in Work pause')
                ->label('Pause work')->color('success')
                ->action(function (){
                    //lo que harmeos es aqui sera algo parecido pero no
                    $user=Auth::user();
                    //verificar que day_out este null entonces salta notificacion
                    $openWorkSession=Timesheet::where('user_id', $user->id)
                        ->where('type', 'work')
                        ->whereNull('day_out')
                        ->latest()
                        ->first();
                        if (!$openWorkSession) {
                            Notification::make()
                                ->title('Trabajo no iniciado') // Notify that no work session has been started
                                ->danger()
                                ->send();
                            return;
                        }
                        $Timesheet=Timesheet::where('user_id', $user->id)
                        ->where('type', 'work')
                        ->WhereNull('day_out')
                        ->latest()
                        ->first();
                        //editamps el timessheet del ese ultimo registro
                        $Timesheet->update([
                            'day_out' => Carbon::now(),
                        ]);
                    Notification::make()
                        ->title('Sesion de trabajo terminado')
                        ->success()
                        ->send();
                }
                )
                ->requiresConfirmation(),
            Action::make('startPause')
                ->label('Comenza a pausar')->color('info')
                ->action(function(){
                    $user=Auth::user();
                    //verificar que day_out este null entonces salta notificacion
                    $openWorkSession=Timesheet::where('user_id', $user->id)
                        ->where('type', 'pause')
                        ->whereNull('day_out')
                        ->latest()
                        ->first();
                        if ($openWorkSession) {
                            Notification::make()
                                ->title('Ya tienes una sesión de pausa activa')
                                ->danger()
                                ->send();
                            return;
                        }
                        //obtenemos el ultimo registro agregado de calendar
                        $calendarUltimate=Calendar::latest()->first();

                        Timesheet::create([
                            'user_id' => $user->id,
                            'calendar_id' =>$calendarUltimate->id,
                            'type' => 'pause',
                            'day_in' => Carbon::now(),
                            'day_out' => null,
                        ]);
                          Notification::make()
                        ->title('¡Sesión de pausa iniciada!')
                        ->success()
                        ->send();
                }),
            Action::make('stopPause')
                ->label('Detener pausar')->color('info')
                ->action(function(){
                    $user=Auth::user();
                    //verificar que day_out este null entonces salta notificacion
                    $openWorkSession=Timesheet::where('user_id', $user->id)
                        ->where('type', 'pause')
                        ->whereNull('day_out')
                        ->latest()
                        ->first();
                        if (!$openWorkSession) {
                            Notification::make()
                                ->title('Pausa no iniciada') // Notify that no work session has been started
                                ->danger()
                                ->send();
                            return;
                        }
                        $Timesheet=Timesheet::where('user_id', $user->id)
                        ->where('type', 'pause')
                        ->WhereNull('day_out')
                        ->latest()
                        ->first();
                        //editamps el timessheet del ese ultimo registro
                        $Timesheet->update([
                            'day_out' => Carbon::now(),
                        ]);
                    Notification::make()
                        ->title('Sesion de pausa terminado')
                        ->success()
                        ->send();
                }),
            Actions\CreateAction::make(),
        ];
    }
}
