<?php

namespace App\Filament\Resources;

use Filament\Forms;
use TextInput\Mask;
use Filament\Tables;
use App\Models\Barang;
use App\Models\Belanja;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use function Laravel\Prompts\select;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\BelanjaResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\BelanjaResource\RelationManagers;

class BelanjaResource extends Resource
{
    protected static ?string $model = Belanja::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationLabel = 'Belanja Barang';
    protected static ?string $navigationGroup = 'Kelola Barang Produksi';

    public static ?string $label = 'Histori Belanja';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                DatePicker::make('tanggal_beli')
                    ->label('Tanggal Pembelian')
                    ->date('d-m-Y')
                    ->required(),

                Select::make('id_barang')
                    ->label('ID Barang')
                    ->placeholder('Pilih Salah Satu ID Barang')
                    ->options(Barang::all()->pluck('id_barang', 'id_barang'))
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        $barang = Barang::find($state);
                        if ($barang) {
                            $set('nama_barang', $barang->nama_barang);
                        }
                    })
                    ->required(),

                Select::make('nama_barang')
                    ->label('Nama Barang')
                    ->placeholder('Pilih Salah Satu Nama Produk')
                    ->options(Barang::all()->pluck('nama_barang', 'nama_barang'))
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        $barang = Barang::where('nama_barang', $state)->first();
                        if ($barang) {
                            $set('id_barang', $barang->id_barang);
                        }
                    })
                    ->required(),

                TextInput::make('jumlah')
                ->label('Jumlah')
                ->numeric()
                ->reactive()
                ->debounce(1000)
                ->afterStateUpdated(function ($state, callable $get, callable $set) {
                    $harga = (float) $get('harga_satuan') ?? 0;
                    $set('total', $harga * (float) $state);
                }),

            TextInput::make('harga_satuan')
                ->label('Harga Satuan')
                ->prefix('Rp')
                ->numeric()
                ->reactive()
                ->debounce(1000)
                ->afterStateUpdated(function ($state, callable $get, callable $set) {
                    $jumlah = (float) $get('jumlah') ?? 0;
                    $set('total', $jumlah * (float) $state);
                }),

            TextInput::make('total')
                ->label('Total')
                ->numeric()
                ->disabled()
                ->dehydrated(),
                        ]);
                }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id_barang')
                    ->label('ID Barang'),
                TextColumn::make('nama_barang')
                    ->label('Nama Barang'),
                TextColumn::make('jumlah'),
                TextColumn::make('barang.satuan')
                    ->label('Satuan'),
                TextColumn::make('harga_satuan')
                    ->label('Harga Satuan')
                    ->formatStateUsing(fn (Belanja $record): string => 'Rp ' . number_format($record->harga_satuan, 0, '.', '.')),
                TextColumn::make('total')
                    ->formatStateUsing(fn (Belanja $record): string => 'Rp ' . number_format($record->total, 0, '.', '.')),
                TextColumn::make('tanggal_beli')
                    ->date('d-m-Y')
                    ->sortable()
                    ->label('Tanggal Beli'),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBelanjas::route('/'),
            'create' => Pages\CreateBelanja::route('/create'),
            'edit' => Pages\EditBelanja::route('/{record}/edit'),
        ];
    }
}
