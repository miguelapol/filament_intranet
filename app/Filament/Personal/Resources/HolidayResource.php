<?php

namespace App\Filament\Personal\Resources;

use App\Filament\Personal\Resources\HolidayResource\Pages;
use App\Filament\Personal\Resources\HolidayResource\RelationManagers;
use App\Models\Holiday;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\SelectFilter;

class HolidayResource extends Resource
{
    protected static ?string $model = Holiday::class;
    protected static ?string $navigationLabel = 'Vacaciones';
    protected static ?string $navigationIcon = 'heroicon-o-calendar-date-range';
    protected static ?string $navigationBadgeTooltip = 'The number of holidays pending';
    public static function getNavigationBadge(): ?string
    {
        //solo para el usuario logeado
        return parent::getEloquentQuery()->where('user_id', auth()->user()->id)->where('type','pending')->count();
    }
    public static function getNavigationBadgeColor(): ?string
{
    return parent::getEloquentQuery()->where('user_id', auth()->user()->id)->where('type','pending')->count()>0?'warning':'primary';
}

    public static function getEloquentQuery(): Builder
{
    //solo para el usuario logeado
    //en este caso se uso esto por que como es para empleados normales
    //pues solo debe mostrar los suyos
    return parent::getEloquentQuery()->where('user_id', auth()->user()->id);
}
    public static function form(Form $form): Form
    {
        return $form
             ->schema([
                Forms\Components\Select::make('calendar_id')
                    //->label('Calendario')
                    //aqui cambiomos en español
                    ->relationship(name:'calendar',titleAttribute:'name')
                    ->required(),
                Forms\Components\DatePicker::make('day')
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
            Tables\Columns\TextColumn::make('day')
                ->date()
                ->sortable(),
            Tables\Columns\TextColumn::make('type')
                 ->badge()
                 //color
                 ->color(fn (string $state): string => match ($state) {
                    'decline' => 'danger',
                    'approved' => 'success',
                    'pending' => 'warning'
                })
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
                //filtros
                           SelectFilter::make('type')
                           ->options([
                                   'decline' => 'Decline',
                                   'approved' => 'Approved',
                                   'pending' => 'Pending',
                           ]),
           ])
           ->actions([
               Tables\Actions\EditAction::make(),
               //delete
               Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListHolidays::route('/'),
            'create' => Pages\CreateHoliday::route('/create'),
            'edit' => Pages\EditHoliday::route('/{record}/edit'),
        ];
    }

}
