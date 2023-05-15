<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Bahan;
use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Finishing;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ProdukResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ProdukResource\RelationManagers;
use App\Filament\Resources\ProdukResource\RelationManagers\BahanRelationManager;
use App\Filament\Resources\ProdukResource\RelationManagers\KategoriRelationManager;
use App\Filament\Resources\ProdukResource\RelationManagers\FinishingRelationManager;
use Illuminate\Database\Eloquent\Model;

class ProdukResource extends Resource
{
    protected static ?string $model = Produk::class;

    protected static ?string $navigationLabel = 'Produk';
    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $navigationGroup = 'Produk';
    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'nama';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(1)
                    ->schema([
                        Forms\Components\Wizard::make()
                            ->schema([
                                Forms\Components\Wizard\Step::make('Info Produk')
                                    ->description('Masukkan info produk')
                                    ->icon('heroicon-o-information-circle')
                                    ->schema([
                                        Forms\Components\Card::make()
                                            ->schema([
                                                Forms\Components\TextInput::make('nama')
                                                    ->label('Nama Produk')
                                                    ->required()
                                                    ->maxLength(255),
                                                Forms\Components\RichEditor::make('deskripsi'),
                                                Forms\Components\TextInput::make('modal')
                                                    ->required()
                                                    ->prefix('Rp')
                                                    ->numeric(),
                                                Forms\Components\TextInput::make('harga')
                                                    ->required()
                                                    ->prefix('Rp')
                                                    ->numeric(),
                                                // Forms\Components\TextInput::make('stok')
                                                //     ->required()
                                                //     ->numeric(),
                                                Forms\Components\Toggle::make('status')
                                                    ->label('Aktif')
                                                    ->required()
                                                    ->inline(false)
                                                    ->onIcon('heroicon-o-check')
                                                    ->onColor('success')
                                                    ->offIcon('heroicon-o-x')
                                                    ->offColor('danger'),
                                            ])->columnSpan(8),

                                        Forms\Components\Card::make()
                                            ->schema([
                                                Forms\Components\Select::make('kategori_id')
                                                    ->label('Kategori')
                                                    ->multiple()
                                                    ->searchable()
                                                    ->relationship('kategori', 'nama')
                                                    ->options(Kategori::all()->pluck('nama', 'id')->toArray()),
                                                Forms\Components\Select::make('bahan_id')
                                                    ->label('Bahan')
                                                    ->searchable()
                                                    ->relationship('bahan', 'nama')
                                                    ->preload()
                                                    ->getOptionLabelFromRecordUsing(fn (Bahan $record) => "{$record->nama}  {$record->berat} {$record->satuan_berat}"),
                                                Forms\Components\Select::make('finishing_id')
                                                    ->label('Finishing')
                                                    ->multiple()
                                                    ->searchable()
                                                    ->relationship('finishing', 'nama')
                                                    ->options(Finishing::all()->pluck('nama', 'id')->toArray()),
                                                Forms\Components\FileUpload::make('foto')
                                                    ->image(),
                                            ])->columnSpan(4),
                                    ]),
                                Forms\Components\Wizard\Step::make('Ukuran Produk')
                                    ->description('Masukkan ukuran produk')
                                    ->icon('heroicon-o-scale')
                                    ->schema([
                                        Forms\Components\Card::make()
                                            ->schema([
                                                Forms\Components\TextInput::make('panjang')
                                                    ->numeric(),
                                                Forms\Components\TextInput::make('lebar')
                                                    ->numeric(),
                                                Forms\Components\TextInput::make('tinggi')
                                                    ->numeric(),
                                                Forms\Components\TextInput::make('satuan_ukuran')
                                                    ->maxLength(255),
                                            ]),
                                    ]),
                            ])->columns(12),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('foto'),
                Tables\Columns\TextColumn::make('nama')->searchable(['nama', 'deskripsi'])->sortable(),
                Tables\Columns\TextColumn::make('modal')->sortable()->prefix('Rp'),
                Tables\Columns\TextColumn::make('harga')->sortable()->prefix('Rp'),
                Tables\Columns\TextColumn::make('stok')->sortable(),
                Tables\Columns\IconColumn::make('status')->boolean()->sortable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime('d F Y'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            KategoriRelationManager::class,
            BahanRelationManager::class,
            FinishingRelationManager::class,
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProduks::route('/'),
            'create' => Pages\CreateProduk::route('/create'),
            'view' => Pages\ViewProduk::route('/{record}'),
            'edit' => Pages\EditProduk::route('/{record}/edit'),
        ];
    }

    protected static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    protected static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Nama' => $record->nama,
            'Harga' => $record->harga,
            'Stok' => $record->stok,
            'Status' => $record->status,
        ];
    }
}
