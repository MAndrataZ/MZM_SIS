<?php

namespace App\Filament\Resources\TransaksiFormResource\Pages;

use Filament\Resources\Pages\Page;
use App\Filament\Resources\TransaksiFormResource;
use Illuminate\Http\RedirectResponse;

class ListTransaksiForms extends Page
{
    protected static string $resource = TransaksiFormResource::class;

    protected static string $view = 'filament.redirects.to-create';

    public function mount(): RedirectResponse
    {
        return redirect(static::getResource()::getUrl('create'));
    }
}
