<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Barang;
use App\Models\Produk;
use Filament\Forms\Get;
use Filament\Forms\Form;
use App\Models\Transaksi;
use Filament\Tables\Table;
use Filament\Forms\Set;
use App\Models\TransaksiForm;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TransaksiFormResource\Pages;
use App\Filament\Resources\TransaksiFormResource\RelationManagers;
use App\Filament\Resources\TransaksiFormResource\Pages\EditTransaksiForm;
use App\Filament\Resources\TransaksiFormResource\Pages\ListTransaksiForms;
use App\Filament\Resources\TransaksiFormResource\Pages\CreateTransaksiForm;

class TransaksiFormResource extends Resource
{
    protected static ?string $model = Transaksi::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
            DatePicker::make('tanggal_transaksi')
                ->required()
                ->columnSpan(2),

            Repeater::make('detailTransaksi')
                ->label('Daftar Produk')
                ->relationship('detailTransaksi') 
                ->dehydrated(false) 
                ->columns(2)
                ->minItems(1)
                ->live()
                ->columnSpan(2)
                ->schema([
                    Select::make('SKU')
                        ->label('Produk')
                        ->options(Produk::all()->pluck('nama_produk', 'SKU'))
                        ->searchable()
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set, Get $get) {
                            $produk = Produk::find($state);
                            if ($produk) {
                                $set('harga_satuan', $produk->harga_retail); // default aja
                                $jumlah = $get('jumlah') ?? 0;
                                $set('total', $produk->harga_retail * $jumlah);
                            }
                        })
                        ->dehydrated(),

                    Select::make('dijual_ke')
                        ->label('Jenis Penjualan')
                        ->options([
                            'retail' => 'Retail',
                            'reseller' => 'Reseller',
                        ])
                        ->reactive()
                        ->afterStateUpdated(function (Set $set, Get $get, $state) {
                            $sku = $get('SKU');
                            $produk = Produk::find($sku);
                            if ($produk) {
                                $harga = $state === 'reseller'
                                    ? $produk->harga_reseller
                                    : $produk->harga_retail;
                                $set('harga_satuan', $harga);
                                $jumlah = $get('jumlah') ?? 0;
                                $set('total', $harga * $jumlah);
                            }
                        })
                        ->dehydrated(),

                    TextInput::make('harga_satuan')
                        ->label('Harga Satuan')
                        ->numeric()
                        ->prefix('Rp')
                        ->disabled()
                        ->dehydrated(),

                    TextInput::make('jumlah')
                        ->label('Jumlah')
                        ->numeric()
                        ->reactive()
                        ->afterStateUpdated(function (Set $set, $state, Get $get) {
                            $harga = $get('harga_satuan') ?? 0;
                            $set('total', $harga * intval($state));

                            // Hindari error array undefined
                            $items = $get('../../detailTransaksi');
                            if (!is_array($items)) {
                                return;
                            }

                            $set('../../produk_terjual', collect($items)->sum('jumlah'));
                            $set('../../total_pendapatan', collect($items)->sum('total'));
                        })
                        ->dehydrated(),

                    TextInput::make('total')
                        ->label('Total')
                        ->numeric()
                        ->disabled()
                        ->dehydrated(),
                ]),

            TextInput::make('produk_terjual')
                ->label('Total Produk Terjual')
                ->disabled()
                ->dehydrated()
                ->numeric()
                ->reactive(),

            TextInput::make('total_pendapatan')
                ->label('Total Pendapatan')
                ->prefix('Rp')
                ->disabled()
                ->dehydrated()
                ->numeric()
                ->reactive(),
        ])
        ->columns(2); 
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            
        ];
    }

    public static function getNavigationUrl(): string
    {
        return static::getUrl('create');
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransaksiForms::route('/'),
            'create' => Pages\CreateTransaksiForm::route('/create'),
            // 'edit' => Pages\EditTransaksiForm::route('/{record}/edit'),
        ];
    }


    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

}
