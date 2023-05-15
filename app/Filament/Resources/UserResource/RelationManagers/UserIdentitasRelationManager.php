<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class UserIdentitasRelationManager extends RelationManager
{
    protected static string $relationship = 'user_identitas';

    protected static ?string $recordTitleAttribute = 'nama_lengkap';

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
                                                // Forms\Components\Select::make('user_id')
                                                //     ->label('User')
                                                //     ->searchable()
                                                //     ->required()
                                                //     ->relationship('user', 'name')
                                                //     ->options(User::all()->pluck('name', 'id')->toArray()),
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
                Tables\Columns\TextColumn::make('nama_lengkap'),
                Tables\Columns\TextColumn::make('telepon'),
                Tables\Columns\TextColumn::make('jalan'),
                Tables\Columns\TextColumn::make('kelurahan'),
                Tables\Columns\TextColumn::make('kecamatan'),
                Tables\Columns\TextColumn::make('kabupaten'),
                Tables\Columns\TextColumn::make('provinsi'),
                Tables\Columns\TextColumn::make('kode_pos'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                // 
            ]);
    }    
}
