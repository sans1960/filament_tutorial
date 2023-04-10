<?php

namespace App\Filament\Resources\EmployeeResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Models\Employee;
use App\Models\Country;

class EmployeeStatsOverview extends BaseWidget
{
    protected function getCards(): array
    {
        $mx = Country::where('country_code','MX')->withCount('employees')->first();
        $us = Country::where('country_code','US')->withCount('employees')->first();
        return [
            Card::make('Total Empleados', Employee::all()->count()),
            Card::make($mx->name .' Empleados', $mx->employees_count),
            Card::make($us->name .' Empleados', $us->employees_count),
          
        ];
    }
}
