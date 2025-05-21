<?php

namespace App\Filament\Resources\HolidayResource\Pages;

use App\Filament\Resources\HolidayResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\HolidayStatusUpdatedNotification;
use App\Models\Holiday;
use Filament\Notifications\Notification;
class EditHoliday extends EditRecord
{
    protected static string $resource = HolidayResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $holiday = $this->record;
        //lo que hacemos es capturar ese id correspondiente
        //para mandarle la notificacion al usuario
        $user=User::find($holiday->user_id);
;       $recipient=$user;
        if($holiday->wasChanged('type') && ($holiday->type ==='approved'||$holiday->type ==='decline')) {
            try {
                Mail::to($holiday->user->email)
                    ->send(new HolidayStatusUpdatedNotification($holiday));

              $estado=$holiday->type==='approved'?'Aprobado':'Rechazado';
             //manda notificacion
                Notification::make()
                    ->title('Estado de vacaciones')
                    //si $holiday->type es approbed es porque ya fue aprobado y si es decline es rechazado
                    ->body("Tu solicitud ha sido ".$estado."")
                    ->info()
                    ->sendToDatabase($recipient);
            } catch (\Exception $e) {
                Log::error('Error al enviar correo de estado de vacaciones: ' . $e->getMessage());
            }
        } else {
            Log::warning('No se pudo enviar correo de estado de vacaciones [APROBADO] porque el usuario o su email no está disponible para holiday ID: ' . $holiday->id);
        }
        //mandar notificacion

    }
}
