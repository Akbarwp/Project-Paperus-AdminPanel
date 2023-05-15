<?php

namespace App\Filament\Resources\PegawaiResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PenggajianRelationManager extends RelationManager
{
    protected static string $relationship = 'penggajian';

    protected static ?string $recordTitleAttribute = 'tanggal';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\DatePicker::make('tanggal')
                            ->required(),
                        Forms\Components\TextInput::make('jumlah_gaji')
                            ->required()
                            ->numeric(),
                        Forms\Components\TextInput::make('jumlah_lembur')
                            ->required()
                            ->numeric()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                $uangLembur = $state * 200000;
                                $gaji = $get('jumlah_gaji');
                                $potongan = $get('potongan');

                                $set('uang_lembur', $uangLembur);
                                $set('total_gaji_diterima', $gaji + $uangLembur - $potongan);
                            }),
                        Forms\Components\Hidden::make('uang_lembur')
                            ->dehydrated(false)
                    ])->columnSpan(6),
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('potongan')
                            ->required()
                            ->numeric()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                $gaji = $get('jumlah_gaji');
                                $uangLembur =  $get('uang_lembur');

                                $set('total_gaji_diterima', $gaji + $uangLembur - $state);
                            }),
                        Forms\Components\TextInput::make('total_gaji_diterima')
                            ->required()
                            ->numeric(),
                    ])->columnSpan(6),
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Textarea::make('keterangan'),
                    ]),
            ])->columns(12);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tanggal')->date('d F Y')->sortable(),
                Tables\Columns\TextColumn::make('keterangan')->limit(21),
                Tables\Columns\TextColumn::make('jumlah_gaji'),
                Tables\Columns\TextColumn::make('jumlah_lembur'),
                Tables\Columns\TextColumn::make('potongan'),
                Tables\Columns\TextColumn::make('total_gaji_diterima'),
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
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }    
}
