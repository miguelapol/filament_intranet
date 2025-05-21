<?php

namespace App\Filament\Personal\Resources\HolidayResource\Pages;

use App\Filament\Personal\Resources\HolidayResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
//Notificaciones
use Filament\Notifications\Notification;
//mail
use App\Mail\NotificationHolidays;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;


class CreateHoliday extends CreateRecord
{
    protected static string $resource = HolidayResource::class;


    protected function mutateFormDataBeforeCreate(array $data): array
    {
        //con esto por fault enviaremos el id del autenficiado y default pendiente
        $data['user_id'] = auth()->user()->id;
        $data['type'] = 'pending';
        $recipient=auth()->user();
        notification::make()
            ->title('Solicitud de vacaciones')
            ->body("El dia ".$data['day']." ha sido solicitado para vacaciones")
            ->warning()
            ->sendToDatabase($recipient);
        return $data;
    }
    protected function afterCreate(): void
    {
        $holiday = $this->record;
        $adminEmail='apolonio1995@hotmail.com';
        try {
            if($adminEmail){
                Mail::to($adminEmail)->send(new NotificationHolidays($holiday));
                Log::info('Correo enviado a: '.$adminEmail);
            }
        } catch (\Exception $e) {
            Log::error('Error al enviar el correo: '.$e->getMessage());
            //throw $th;
        }
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
