<?php

namespace App\Filament\Pages;

use Filament\Forms;
use Filament\Tables;
use Filament\Pages\Page;
use App\Models\Transaksi;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;
use Filament\Tables\Actions\Action;

class LaporanTransaksi extends Page implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.pages.laporan-transaksi';
    protected static ?string $navigationLabel = 'Laporan Transaksi';
    protected static ?int $navigationSort = 99;
    protected static ?string $title = 'Laporan Transaksi';

    public ?string $tanggalAwal = null;
    public ?string $tanggalAkhir = null;

    protected function getTableQuery()
    {
        $query = Transaksi::query();

        if ($this->tanggalAwal && $this->tanggalAkhir) {
            $query->whereBetween('tanggal_transaksi', [
                Carbon::parse($this->tanggalAwal)->startOfDay(),
                Carbon::parse($this->tanggalAkhir)->endOfDay(),
            ]);
        }

        return $query;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                Tables\Columns\TextColumn::make('index')->label('No')->rowIndex(),
                Tables\Columns\TextColumn::make('id_transaksi')->label('ID'),
                Tables\Columns\TextColumn::make('tanggal_transaksi')->dateTime()->date('d-m-Y'),
                Tables\Columns\TextColumn::make('produk_terjual'),
                Tables\Columns\TextColumn::make('total_pendapatan')->money('IDR'),
                // Tables\Columns\TextColumn::make('id_pengguna'),
            ])
            ->actions([
                Action::make('View')
                    ->label('Lihat')
                    ->icon('heroicon-m-eye')
                    ->url(fn (Transaksi $record) => route('filament.admin.pages.detail-transaksi', ['id' => $record->id_transaksi])),
            ]);
    }


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\DatePicker::make('tanggalAwal')->label('Tanggal Awal'),
                        Forms\Components\DatePicker::make('tanggalAkhir')->label('Tanggal Akhir'),
                    ]),
                Forms\Components\Actions::make([
                    Forms\Components\Actions\Action::make('Filter')->action('applyFilter')->label('Terapkan Filter'),
                ]),
            ]);
    }

    public function applyFilter()
    {
        // refresh table
    }
}
