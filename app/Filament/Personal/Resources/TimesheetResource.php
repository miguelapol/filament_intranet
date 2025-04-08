<?php

namespace App\Filament\Personal\Resources;

use App\Filament\Personal\Resources\TimesheetResource\Pages;
use App\Filament\Personal\Resources\TimesheetResource\RelationManagers;
use App\Models\Timesheet;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\Hidden;

class TimesheetResource extends Resource
{
    protected static ?string $model = Timesheet::class;

    protected static ?string $navigationIcon = 'heroicon-o-table-cells';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Select::make('calendar_id')
                //->label('Calendario')
                //aqui cambiomos en español
                ->relationship(name:'calendar',titleAttribute:'name')
                ->required(),
            Hidden::make('user_id')
                ->default(auth()->id()),
            Forms\Components\Select::make('type')
                ->options([
                    //lado izquierdo valor, lado derecho nombre
                    'work' => 'Working',
                    'pause' => 'In Pause',
                ])
                ->required(),
            Forms\Components\DateTimePicker::make('day_in')
                ->required(),
            Forms\Components\DateTimePicker::make('day_out')
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            Tables\Columns\TextColumn::make('calendar.name')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('type')
                ->searchable(),
            Tables\Columns\TextColumn::make('day_in')
                ->dateTime()
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('day_out')
                ->dateTime()
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('updated_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ])
        ->filters([
            //filtros para cuando estan en working o pause
            SelectFilter::make('type')
                ->options([
                    'work' => 'working',
                    'pause' => 'Pause',
                ]),

        ])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make()

        ])
        ->bulkActions([
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
            ]),
        ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTimesheets::route('/'),
            // 'create' => Pages\CreateTimesheet::route('/create'),
            // 'edit' => Pages\EditTimesheet::route('/{record}/edit'),
        ];
    }
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('user_id', auth()->user()->id);
    }
}
