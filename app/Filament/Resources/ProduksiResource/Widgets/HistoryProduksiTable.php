<?php

namespace App\Filament\Resources\ProduksiResource\Widgets;

use App\Models\Produksi;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Filament\Widgets\TableWidget as BaseWidget;

class HistoryProduksiTable extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    protected function getTableQuery(): Builder
    {
        $idProduksi = request()->route('record'); // PRD0001, dst
        $produksi = \App\Models\Produksi::find($idProduksi);

        return Produksi::query()
            ->where('SKU', $produksi?->SKU ?? '-');
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('tanggal_produksi')
                ->label('Tanggal Produksi')
                ->date(),

            Tables\Columns\TextColumn::make('jumlah_produksi')
                ->label('Jumlah Produksi'),

            Tables\Columns\TextColumn::make('total_modal')
                ->label('Total Modal (Rp.)')
                ->money('IDR'),
        ];
    }
}
