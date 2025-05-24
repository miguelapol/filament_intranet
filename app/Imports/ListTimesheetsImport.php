<?php

namespace App\Imports;

//calendar
use App\Models\Calendar;
//carbon para fecha
use Carbon\Carbon;
use App\Models\Timesheet;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
//auth
use Illuminate\Support\Facades\Auth;

//collection
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

use Filament\Notifications\Notification;


class ListTimesheetsImport implements ToCollection, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $rows)
    {

    foreach ($rows as $row) {
        $calendar_id = Calendar::where('name', $row['calendario'])->first();

        if ($calendar_id === null) {
            Notification::make()
                ->title('Error de importación')
                ->warning()
                ->body('No existe el calendario: ' . $row['calendario'])
                ->send();
            return;
        }
         Timesheet::create([
            'calendar_id' => $calendar_id->id,
            'user_id' => Auth::id(),
            'type' => $row['tipo'],
            'day_in' => $row['hora_de_entrada'],
            'day_out' => $row['hora_de_salida'],
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

    }
        Notification::make()
            ->title('Importación exitosa')
            ->success()
            ->body('Se han importado correctamente los registros de asistencia.')
            ->send();
    }
}
