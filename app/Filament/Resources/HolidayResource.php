<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HolidayResource\Pages;
use App\Filament\Resources\HolidayResource\RelationManagers;
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
    protected static ?string $navigationGroup='Employees Management';
    protected static ?int $navigationSort=3;
    protected static ?string $navigationIcon = 'heroicon-o-calendar-date-range';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('calendar_id')
                    //->label('Calendario')
                    //aqui cambiomos en español
                    ->relationship(name:'calendar',titleAttribute:'name')
                    ->required(),
                Forms\Components\Select::make('user_id')
                    ->relationship(name:'user',titleAttribute:'name')
                    ->required(),
                Forms\Components\Select::make('type')
                    ->options([
                        //lado izquierdo valor, lado derecho nombre
                        'decline' => 'Declined',
                        'approved' => 'Approved',
                        'pending' => 'Pending',
                    ])
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
                Tables\Columns\TextColumn::make('user.name')
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
