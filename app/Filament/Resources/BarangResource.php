<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Barang;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\BarangResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\BarangResource\RelationManagers;

class BarangResource extends Resource
{
    protected static ?string $model = Barang::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';
    protected static ?string $navigationLabel = 'List Barang';
    protected static ?string $navigationGroup = 'Kelola Barang Produksi';

    public static ?string $label = 'List Barang';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('id_barang')
                    ->required()
                    ->placeholder('Masukkan ID Barang')
                    ->label('ID Barang'),
                TextInput::make('nama_barang')
                    ->required()
                    ->placeholder('Masukkan Nama Barang')
                    ->label('Nama Barang'),
                Select::make('satuan')
                ->required()
                ->label('Satuan')
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

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('index')->label('No')->rowIndex(),
                TextColumn::make('id_barang')
                    ->label('ID Barang'),
                TextColumn::make('nama_barang')
                    ->label('Nama Barang')
                    -> searchable(),
                TextColumn::make('satuan'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
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
            'index' => Pages\ListBarangs::route('/'),
            'create' => Pages\CreateBarang::route('/create'),
            'edit' => Pages\EditBarang::route('/{record}/edit'),
        ];
    }
}
