<?php

namespace App\Filament\Personal\Resources\TimesheetResource\Pages;

use App\Filament\Personal\Resources\TimesheetResource;
use Filament\Actions;
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

class ListTimesheets extends ListRecords

{
    protected static string $resource = TimesheetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('in Work')
                ->label('Entrar a trabajar')->color('success')
                ->requiresConfirmation(),
            Action::make('in Pause')
                ->label('Comenza a pausar')->color('info')
                ->requiresConfirmation(),
            Actions\CreateAction::make(),
        ];
    }
}
