<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeResource\Pages;
use App\Filament\Resources\EmployeeResource\RelationManagers;
use App\Models\Employee;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Card;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use App\Filament\Resources\EmployeeResource\Widgets\EmployeeStatsOverview;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationLabel = 'Empleados';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                ->schema([
                 
                 Select::make('country_id')
                     ->label('Country')
                     ->options(Country::all()->pluck('name','id')->toArray())
                     ->reactive()
                     ->required()
                     ->afterStateUpdated(fn (callable $set)=>$set('state_id',null)),
                 Select::make('state_id')
                      ->label('State')
                      ->options(function (callable $get){
                        $country = Country::find($get('country_id'));
                        if(!$country){
                            return State::all()->pluck('name','id');
                        }
                        return $country->states->pluck('name','id');
                      })
                      ->reactive()
                      ->required()
                      ->afterStateUpdated(fn (callable $set)=>$set('city_id',null)),
                Select::make('city_id')
                      ->label('City')
                      ->options(function (callable $get){
                        $state = State::find($get('state_id'));
                        if(!$state){
                            return City::all()->pluck('name','id');
                        }
                        return $state->cities->pluck('name','id');
                      })
                      ->reactive()
                      ->required(),
                      
                    
                 Select::make('department_id')
                     ->relationship('department', 'name') ->required(),
                 TextInput::make('first_name')->required()->maxLength(100),
                 TextInput::make('last_name')->required()->maxLength(100),
                 TextInput::make('phone_number')->required()->maxLength(20),
                 TextInput::make('address')->required()->maxLength(200),
                 TextInput::make('zip_code')->required()->maxLength(20),
                 DatePicker::make('birth_date')->required(),
                 DatePicker::make('date_hired')->required()
              ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('first_name')->sortable()->searchable()->toggleable(),
                TextColumn::make('last_name')->sortable()->searchable()->toggleable(),
                TextColumn::make('phone_number')->sortable()->searchable()->toggleable(),
                TextColumn::make('address')->sortable()->searchable()->toggleable(),
                TextColumn::make('zip_code')->sortable()->searchable()->toggleable(),
                TextColumn::make('country.name')->sortable()->toggleable(),
                TextColumn::make('state.name')->sortable()->toggleable(),
                TextColumn::make('city.name')->sortable()->toggleable(),
                TextColumn::make('department.name')->sortable()->toggleable(),
                
                TextColumn::make('date_hired')->dateTime()->toggleable(),
                TextColumn::make('birth_date')->dateTime()->toggleable()
            ])
            ->filters([
                SelectFilter::make('department')->relationship('department', 'name')
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                ExportBulkAction::make(),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    public static function getWidgets(): array
    {
        return [
            EmployeeStatsOverview::class,
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }    
}
