<?php

namespace App\Filament\Resources\BelanjaResource\Pages;

use App\Filament\Resources\BelanjaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBelanjas extends ListRecords
{
    protected static string $resource = BelanjaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Belanja'),
        ];
    }

    public function getBreadcrumb(): string
    {
        return 'Histori Belanja';
    }
    
}
