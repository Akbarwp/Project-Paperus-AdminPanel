<?php

namespace App\Filament\Resources;

use Closure;
use Filament\Forms;
use Filament\Tables;
use App\Models\Sales;
use App\Models\Produk;
use App\Models\SalesDetail;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SalesDetailResource\Pages;
use App\Filament\Resources\SalesDetailResource\RelationManagers;
use App\Filament\Resources\SalesDetailResource\RelationManagers\ProdukRelationManager;

class SalesDetailResource extends Resource
{
    protected static ?string $model = SalesDetail::class;

    protected static ?string $navigationLabel = 'Sales Detail';
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-list';
    protected static ?string $navigationGroup = 'Penjualan';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Select::make('sales_id')
                            ->label('Sales ID')
                            ->required()
                            ->searchable()
                            ->options(Sales::where('status', '=', 'baru')->get()->pluck('id', 'id')->toArray()),
                        
                        Forms\Components\Grid::make()
                            ->schema([
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
                                            $set('modal', $produk->modal);
                                        } else {
                                            $set('harga_produk', '');
                                            $set('modal', '');
                                        }
                                    }),

                                Forms\Components\TextInput::make('harga_produk')
                                    ->disabled()
                                    ->prefix('Rp')
                                    ->dehydrated(false),
                                Forms\Components\Hidden::make('modal')
                                    ->disabled()
                                    ->dehydrated(false),
                                Forms\Components\Hidden::make('keuntungan')
                                    ->disabled(),
                            ]),
                        Forms\Components\TextInput::make('kuantitas')
                            ->required()
                            ->numeric()
                            ->reactive()
                            ->maxValue(function (callable $get) {
                                $produk = Produk::find($get('produk_id'));
                                if (!$produk) {
                                    return 10000;
                                }
                                return $produk->stok;
                            })
                            ->suffix(function (callable $get) {
                                $produk = Produk::find($get('produk_id'));
                                if (!$produk) {
                                    return "Stok: ";
                                }
                                return "Stok: " . $produk->stok;
                            })
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                $harga = $get('harga_produk');
                                $modal = $get('modal');
                                $untung = $harga - $modal;
                                $diskon = $get('diskon');

                                $total = $harga * $state;
                                $keuntungan = $untung * $state;

                                $set('total', $total - (($total*$diskon)/100));
                                $set('keuntungan', $keuntungan);
                            }),
                        Forms\Components\TextInput::make('total')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->disabled(),
                        Forms\Components\TextInput::make('diskon')
                            ->numeric()
                            ->suffix('%')
                            ->reactive()
                            ->afterStateUpdated(function (callable $set, callable $get) {
                                $harga = $get('harga_produk');
                                $kuantitas = $get('kuantitas');
                                $diskon = $get('diskon');
                                $total = $harga * $kuantitas;
                                $set('total', $total - (($total*$diskon)/100));
                            }),
                        Forms\Components\Textarea::make('keterangan')
                            ->maxLength(65535),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sales_id')->sortable(),
                Tables\Columns\TextColumn::make('produk_id'),
                Tables\Columns\TextColumn::make('kuantitas')->sortable(),
                Tables\Columns\TextColumn::make('total')->sortable()->prefix('Rp'),
                Tables\Columns\TextColumn::make('keuntungan')->sortable()->prefix('Rp'),
            ])
            ->filters([
                // 
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->using(function (Model $record) {
                        $oldProduk = Produk::find($record['produk_id']);
                        $oldSalesDetail = SalesDetail::find($record['id']);

                        $oldProduk->update([
                            'stok' => $oldProduk->stok + $oldSalesDetail->kuantitas
                        ]);

                        $sales = Sales::find($record['sales_id']);
                        $sales->update([
                            'grand_total' => $sales->grand_total - $oldSalesDetail['total'],
                        ]);

                        $sales = $record->delete();

                        return $sales;
                    }),
            ])
            ->bulkActions([
                // Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListSalesDetails::route('/'),
            'create' => Pages\CreateSalesDetail::route('/create'),
            'edit' => Pages\EditSalesDetail::route('/{record}/edit'),
        ];
    }    
}
