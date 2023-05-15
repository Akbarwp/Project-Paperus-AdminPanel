<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Resources\Form;
use App\Models\UserIdentitas;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserIdentitasResource\Pages;
use App\Filament\Resources\UserIdentitasResource\RelationManagers;

class UserIdentitasResource extends Resource
{
    protected static ?string $model = UserIdentitas::class;

    protected static ?string $navigationIcon = 'heroicon-o-identification';
    protected static ?string $navigationGroup = 'Users';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(1)
                    ->schema([
                        Forms\Components\Wizard::make()
                            ->schema([
                                Forms\Components\Wizard\Step::make('User Info')
                                    ->description('Masukkan identias user')
                                    ->icon('heroicon-o-identification')
                                    ->schema([
                                        Forms\Components\Card::make()
                                            ->schema([
                                                Forms\Components\Select::make('user_id')
                                                    ->label('User')
                                                    ->searchable()
                                                    ->required()
                                                    ->relationship('user', 'name')
                                                    ->options(User::all()->pluck('name', 'id')->toArray()),
                                                Forms\Components\TextInput::make('nama_lengkap')
                                                    ->required()
                                                    ->maxLength(255),
                                                Forms\Components\TextInput::make('telepon')
                                                    ->required()
                                                    ->maxLength(255),
                                            ]),
                                    ]),
                                Forms\Components\Wizard\Step::make('Alamat')
                                    ->description('Masukkan alamat user')
                                    ->icon('heroicon-o-location-marker')
                                    ->schema([
                                        Forms\Components\Card::make()
                                            ->schema([
                                                Forms\Components\TextInput::make('jalan')
                                                    ->maxLength(255),
                                                Forms\Components\TextInput::make('kelurahan')
                                                    ->maxLength(255),
                                                Forms\Components\TextInput::make('kecamatan')
                                                    ->maxLength(255),
                                                Forms\Components\TextInput::make('kabupaten')
                                                    ->maxLength(255),
                                                Forms\Components\TextInput::make('provinsi')
                                                    ->maxLength(255),
                                                Forms\Components\TextInput::make('kode_pos')
                                                    ->maxLength(255),
                                            ]),
                                    ]),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_lengkap')->searchable()->sortable()->limit(10),
                Tables\Columns\TextColumn::make('telepon')->searchable()->limit(5),
                Tables\Columns\TextColumn::make('jalan')->searchable()->limit(10),
                Tables\Columns\TextColumn::make('kelurahan')->searchable()->limit(10),
                Tables\Columns\TextColumn::make('kecamatan')->searchable()->limit(10),
                Tables\Columns\TextColumn::make('kabupaten')->searchable()->limit(10),
                Tables\Columns\TextColumn::make('provinsi')->searchable()->limit(10),
                Tables\Columns\TextColumn::make('kode_pos')->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListUserIdentitas::route('/'),
            'create' => Pages\CreateUserIdentitas::route('/create'),
            'edit' => Pages\EditUserIdentitas::route('/{record}/edit'),
        ];
    }    
}
