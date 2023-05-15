<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BahanResource\Pages;
use App\Filament\Resources\BahanResource\RelationManagers;
use App\Models\Bahan;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BahanResource extends Resource
{
    protected static ?string $model = Bahan::class;

    protected static ?string $navigationLabel = 'Bahan';
    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $navigationGroup = 'Produk';
    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'nama';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('nama')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('berat')
                            ->numeric(),
                        Forms\Components\TextInput::make('satuan_berat')
                            ->maxLength(255),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('berat'),
                Tables\Columns\TextColumn::make('satuan_berat'),
                // Tables\Columns\TextColumn::make('finishing')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
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
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBahans::route('/'),
            'create' => Pages\CreateBahan::route('/create'),
            'edit' => Pages\EditBahan::route('/{record}/edit'),
        ];
    }

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->nama . " " . $record->berat . " " . $record->satuan_berat;
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Nama' => $record->nama,
            'Berat' => $record->berat,
            'Satuan' => $record->satuan_berat,
        ];
    }
}
