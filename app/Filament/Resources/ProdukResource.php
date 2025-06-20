<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Produk;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ProdukResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ProdukResource\RelationManagers;

class ProdukResource extends Resource
{
    protected static ?string $model = Produk::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'List Produk';
    protected static ?string $navigationGroup = 'Kelola Produk';

    public static ?string $label = 'List Produk';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('SKU')
                    ->required()
                    ->placeholder('Masukkan SKU')
                    ->label('SKU'),
                TextInput::make('nama_produk')
                    ->required()
                    ->placeholder('Masukkan Nama Produk')
                    ->label('Nama Produk'),
                TextInput::make('netto')
                    ->label('Netto')
                    ->numeric()
                    ->required()
                    ->placeholder('Masukkan jumlah netto'),

                Select::make('satuan')
                    ->label('Satuan')
                    ->required()
                    ->options([
                        'gr' => 'Gram (gr)',
                        'kg' => 'Kilogram (kg)',
                        'ml' => 'Mililiter (ml)',
                        'l' => 'Liter (l)',
                        'pcs' => 'Pieces (pcs)',
                        'pak' => 'Pak',
                        'bungkus' => 'Bungkus',
                        'sachet' => 'Sachet',
                        'kaleng' => 'Kaleng',
                        'botol' => 'Botol',
                        'buah' => 'Buah',
                        'ikat' => 'Ikat',
                        'karung' => 'Karung',
                        'dus' => 'Dus',
                    ])
                    ->placeholder('Pilih satuan'),
                TextInput::make('modal')
                    ->prefix('Rp')
                    ->required()
                    ->numeric(),
                TextInput::make('harga_reseller')
                    ->prefix('Rp')
                    ->required()
                    ->numeric(),
                TextInput::make('harga_retail')
                    ->prefix('Rp')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('SKU')
                    ->label('SKU'),
                TextColumn::make('nama_produk')
                    ->label('Nama Produk'),
                TextColumn::make('harga_retail')
                    ->label('Harga Retail')
                    ->formatStateUsing(fn (Produk $record): string => 'Rp ' . number_format($record->harga_retail, 0, '.', '.')),
                TextColumn::make('harga_reseller')
                    ->label('Harga Reseller')
                    ->formatStateUsing(fn (Produk $record): string => 'Rp ' . number_format($record->harga_reseller, 0, '.', '.')),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListProduks::route('/'),
            'create' => Pages\CreateProduk::route('/create'),
            'edit' => Pages\EditProduk::route('/{record}/edit'),
        ];
    }
}
