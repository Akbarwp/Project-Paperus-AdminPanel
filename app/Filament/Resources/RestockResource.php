<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Produk;
use App\Models\Restock;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\RestockResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\RestockResource\RelationManagers;

class RestockResource extends Resource
{
    protected static ?string $model = Restock::class;

    protected static ?string $navigationLabel = 'Restock Produk';
    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $navigationGroup = 'Produk';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\DatePicker::make('tanggal')
                            ->required(),
                        Forms\Components\Select::make('produk_id')
                                    ->label('Produk')
                                    ->required()
                                    ->searchable()
                                    ->options(Produk::all()->pluck('nama', 'id')->toArray())
                                    ->reactive()
                                    ->afterStateUpdated(fn (callable $set) => $set('kuantitas', null))
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        $produk = Produk::find($state);
                                        if ($produk) {
                                            $set('harga_produk', $produk->harga);
                                        } else {
                                            $set('harga_produk', '');
                                        }
                                    }),
                        Forms\Components\TextInput::make('stok')
                            ->label('Jumlah')
                            ->required()
                            ->numeric(),
                    ])->columnSpan(6),
                    
            ])->columns(12);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('produk_id')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('stok'),
                Tables\Columns\TextColumn::make('tanggal')->date('d F Y'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->using(function (Model $record) {
                        $oldProduk = Produk::find($record['produk_id']);

                        $oldProduk->update([
                            'stok' => $oldProduk->stok - $record->stok
                        ]);

                        $restock = $record->delete();

                        return $restock;
                    }),
            ])
            ->bulkActions([
                // 
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
            'index' => Pages\ListRestocks::route('/'),
            'create' => Pages\CreateRestock::route('/create'),
            'edit' => Pages\EditRestock::route('/{record}/edit'),
        ];
    }    
}
