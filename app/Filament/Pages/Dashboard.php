<?php

namespace App\Filament\Pages;

use Filament\Forms;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Forms\Form;
use Filament\Forms\Get;

class Dashboard extends BaseDashboard
{
    use BaseDashboard\Concerns\HasFiltersForm;

    public function filtersForm(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Filter Laporan')
                ->schema([
                    Forms\Components\DatePicker::make('startDate')
                        ->label('Dari Tanggal')
                        ->maxDate(fn(Get $get) => $get('endDate') ?: now()),
                    Forms\Components\DatePicker::make('endDate')
                        ->label('Sampai Tanggal')
                        ->minDate(fn(Get $get) => $get('startDate') ?: now())
                        ->maxDate(now()),
                ])
                ->columns(2)
        ]);
    }
}
