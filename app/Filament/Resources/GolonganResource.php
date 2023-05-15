<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GolonganResource\Pages;
use App\Filament\Resources\GolonganResource\RelationManagers;
use App\Models\Golongan;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GolonganResource extends Resource
{
    protected static ?string $model = Golongan::class;

    protected static ?string $navigationLabel = 'Golongan';
    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?string $navigationGroup = 'Kepegawaian';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('nama')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('gaji_pokok')
                            ->required()
                            ->numeric(),
                        Forms\Components\TextInput::make('tunjangan_transport')
                            ->numeric(),
                        Forms\Components\TextInput::make('tunjangan_makan')
                            ->numeric(),
                    ])->columnSpan(8),

                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('tunjangan_istri')
                            ->numeric(),
                        Forms\Components\TextInput::make('tunjangan_anak')
                            ->numeric(),
                    ])->columnSpan(4),
            ])->columns(12);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama'),
                Tables\Columns\TextColumn::make('gaji_pokok'),
                Tables\Columns\TextColumn::make('tunjangan_istri'),
                Tables\Columns\TextColumn::make('tunjangan_anak'),
                Tables\Columns\TextColumn::make('tunjangan_transport'),
                Tables\Columns\TextColumn::make('tunjangan_makan'),
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
            'index' => Pages\ListGolongans::route('/'),
            'create' => Pages\CreateGolongan::route('/create'),
            'edit' => Pages\EditGolongan::route('/{record}/edit'),
        ];
    }    
}
