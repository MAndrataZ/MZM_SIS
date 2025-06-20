<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LaporanResource\Pages;
use App\Filament\Resources\LaporanResource\RelationManagers;
use App\Models\Laporan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LaporanResource extends Resource
{
    protected static ?string $model = Laporan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->join('transaksi', 'transaksi.id_transaksi', '=', 'detail_transaksi.id_transaksi')
            ->select(
                'transaksi.id_transaksi',
                'transaksi.tanggal_transaksi',
                'transaksi.id_pengguna',
                'detail_transaksi.nama_produk',
                'detail_transaksi.dijual_ke',
                'detail_transaksi.harga_satuan',
                'detail_transaksi.jumlah',
                'detail_transaksi.total'
            );
    }



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_transaksi'),
                Tables\Columns\TextColumn::make('tanggal_transaksi')->dateTime('d-m-Y H:i'),
                Tables\Columns\TextColumn::make('id_pengguna'),
                Tables\Columns\TextColumn::make('nama_produk'),
                Tables\Columns\TextColumn::make('dijual_ke'),
                Tables\Columns\TextColumn::make('harga_satuan')->money('IDR'),
                Tables\Columns\TextColumn::make('jumlah'),
                Tables\Columns\TextColumn::make('total')->money('IDR'),
            ])
            ->filters([])
            ->actions([]) // Hilangkan EditAction
            ->bulkActions([]); // Hilangkan Bulk Delete
    }


    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLaporans::route('/'),
            // 'create' => Pages\CreateLaporan::route('/create'),
            // 'edit' => Pages\EditLaporan::route('/{record}/edit'),
        ];
    }
}
