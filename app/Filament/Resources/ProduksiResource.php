<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Produk;
use App\Models\Produksi;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ProduksiResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ProduksiResource\RelationManagers;

class ProduksiResource extends Resource
{
    protected static ?string $model = Produksi::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Stok & Produksi';
    protected static ?string $navigationGroup = 'Kelola Produk';

    public static ?string $label = 'Stok Produk';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                DatePicker::make('tanggal_produksi')
                    ->label('Tanggal Produksi')
                    ->required(),

                Select::make('SKU')
                    ->label('SKU')
                    ->options(Produk::all()->pluck('SKU', 'SKU'))
                    ->reactive()
                    ->debounce(1000)
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        $produk = Produk::find($state);
                        if ($produk) {
                            $set('nama_produk', $produk->nama_produk);
                            $set('stok_produk', $produk->stok);
                            $set('modal_produk', $produk->modal);

                            $jumlahProduksi = $get('jumlah_produksi');
                            if ($jumlahProduksi) {
                                $set('total_modal', $produk->modal * $jumlahProduksi);
                            }
                        }
                    })
                    ->required(),

                Select::make('nama_produk')
                    ->label('Nama Produk')
                    ->options(Produk::all()->pluck('nama_produk', 'nama_produk'))
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        $produk = Produk::where('nama_produk', $state)->first();
                        if ($produk) {
                            $set('SKU', $produk->SKU);
                            $set('stok_produk', $produk->stok);
                            $set('modal_produk', $produk->modal);

                            $jumlahProduksi = $get('jumlah_produksi');
                            if ($jumlahProduksi) {
                                $set('total_modal', $produk->modal * $jumlahProduksi);
                            }
                        }
                    })
                    ->required(),

                TextInput::make('jumlah_produksi')
                    ->label('Jumlah Produksi')
                    ->numeric()
                    ->reactive()
                    ->required()
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        $sku = $get('SKU');
                        $produk = Produk::find($sku);
                        if ($produk) {
                            $set('total_modal', $produk->modal * $state);
                        }
                    }),

                TextInput::make('stok_produk')
                    ->label('Stok Saat Ini')
                    ->disabled()
                    ->dehydrated(false),

                TextInput::make('total_modal')
                    ->label('Total Modal')
                    ->prefix('Rp')
                    ->disabled()
                    ->dehydrated(false),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->query(Produk::query()) // Menampilkan produk, bukan produksi
            ->columns([
                TextColumn::make('index')->label('No')->rowIndex(),
                Tables\Columns\TextColumn::make('SKU')->
                label('SKU')
                ->searchable(),
                Tables\Columns\TextColumn::make('nama_produk')
                ->label('Nama Produk')
                ->searchable(),
                Tables\Columns\TextColumn::make('stok')
                ->label('Stok Saat Ini')
                ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Detail / Ubah Stok')
                    ->url(function ($record) {
                        // Ambil id_produksi terakhir dari SKU
                        $lastProduksi = \App\Models\Produksi::where('SKU', $record->SKU)
                            ->latest('tanggal_produksi')
                            ->first();

                        return $lastProduksi
                            ? route('filament.admin.resources.produksis.edit', ['record' => $lastProduksi->id_produksi])
                            : '#';
                    }),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery();
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
            'index' => Pages\ListProduksis::route('/'),
            'create' => Pages\CreateProduksi::route('/create'),
            'edit' => Pages\EditProduksi::route('/{record}/edit'),
        ];
    }
}
