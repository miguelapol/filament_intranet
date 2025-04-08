<?php

namespace App\Filament\Personal\Resources\HolidayResource\Pages;

use App\Filament\Personal\Resources\HolidayResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;


class CreateHoliday extends CreateRecord
{
    protected static string $resource = HolidayResource::class;


    protected function mutateFormDataBeforeCreate(array $data): array
    {
        //con esto por fault enviaremos el aid del autenficiado y default pendiente
        $data['user_id'] = auth()->user()->id;
        $data['type'] = 'pending';

        return $data;
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
