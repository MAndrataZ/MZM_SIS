<?php

namespace App\Filament\Resources\BelanjaResource\Pages;

use Filament\Actions;
use App\Models\Barang;
use App\Filament\Resources\BarangResource;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\BelanjaResource;

class CreateBelanja extends CreateRecord
{
    protected static string $resource = BelanjaResource::class;

    protected function getRedirectUrl(): string
    {
        return BelanjaResource::getUrl('index');
    }
}
