<?php

namespace App\Filament\Resources\TransaksiFormResource\Pages;

use App\Filament\Resources\TransaksiFormResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTransaksiForm extends EditRecord
{
    protected static string $resource = TransaksiFormResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
