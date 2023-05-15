<?php

namespace App\Filament\Resources\SalesResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use App\Models\Sales;
use App\Models\Produk;
use App\Models\SalesDetail;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Contracts\HasRelationshipTable;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class SalesdetailRelationManager extends RelationManager
{
    protected static string $relationship = 'salesdetail';

    protected static ?string $recordTitleAttribute = 'produk_id';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
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
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
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
                Tables\Columns\TextColumn::make('produk_id'),
                Tables\Columns\TextColumn::make('kuantitas')->sortable(),
                Tables\Columns\TextColumn::make('total')->sortable()->prefix('Rp'),
                Tables\Columns\TextColumn::make('keuntungan')->sortable()->prefix('Rp'),
                Tables\Columns\TextColumn::make('diskon')->sortable(),
                Tables\Columns\TextColumn::make('keterangan')->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->using(function (HasRelationshipTable $livewire, array $data): Model {
                        $sales = $livewire->getRelationship()->create($data);


                        $produk = Produk::find($sales['produk_id']);

                        $hasil = Produk::find($sales['produk_id'])->update([
                            'stok' => $produk->stok - $sales['kuantitas']
                        ]);

                        $salesTotal = Sales::find($livewire->getRelationship()->get()[0]->sales_id);
                        $salesTotal->update([
                            'grand_total' => $salesTotal['grand_total'] + $data['total'],
                        ]);

                        return $sales;
                    })
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->using(function (Model $record, array $data) {
                        
                        $produk = Produk::find($data['produk_id']);
                        $oldProduk = Produk::find($record['produk_id']);
                        $oldSalesDetail = SalesDetail::find($record['id']);

                        if ($data['produk_id'] != $record->produk_id) {
                            
                            $oldProduk->update([
                                'stok' => $oldProduk->stok + $oldSalesDetail->kuantitas
                            ]);
                            $produk->update([
                                'stok' => $produk->stok - $data['kuantitas']
                            ]);

                        } else if ($data['produk_id'] == $record->produk_id) {

                            $produk->update([
                                'stok' => $produk->stok + $oldSalesDetail->kuantitas
                            ]);
                            $produk->update([
                                'stok' => $produk->stok - $data['kuantitas']
                            ]);
                        }

                        $sales = Sales::find($record['sales_id']);
                        $sales->update([
                            'grand_total' => $sales->grand_total - $oldSalesDetail['total'],
                        ]);
                        $sales->update([
                            'grand_total' => $sales->grand_total + $data['total'],
                        ]);

                        $sales = $record->update($data);

                        return $sales;
                    }),
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
                // 
            ]);
    }    
}
