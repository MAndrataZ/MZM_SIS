<?php

namespace App\Filament\Widgets;

use App\Models\Transaksi;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ViewAction;

class LaporanWidget extends BaseWidget
{
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';

    protected function getTableQuery(): Builder
    {
        return Transaksi::query()->latest();
    }
    protected function getTableActions(): array
    {
        return [
            Action::make('Lihat Detail')
                ->label('Lihat')
                ->icon('heroicon-m-eye')
                ->url(fn(Transaksi $record) => '/admin/detail-transaksi/' . $record->id_transaksi),
        ];
    }
    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('index')->label('No')->rowIndex(),
            TextColumn::make('id_transaksi')->label('ID Transaksi'),
            TextColumn::make('tanggal_transaksi')->label('Tanggal')->date('d-m-Y'),
            TextColumn::make('produk_terjual')->label('Produk Terjual'),
            TextColumn::make('total_pendapatan')->label('Total Pendapatan')->money('IDR'),
            // TextColumn::make('id_pengguna')->label('ID Pengguna'),
        ];
    }
}
