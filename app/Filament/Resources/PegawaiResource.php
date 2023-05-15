<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Pegawai;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\PegawaiResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PegawaiResource\RelationManagers;
use App\Filament\Resources\PegawaiResource\RelationManagers\CutiRelationManager;
use App\Filament\Resources\PegawaiResource\RelationManagers\LemburRelationManager;
use App\Filament\Resources\PegawaiResource\RelationManagers\GolonganRelationManager;
use App\Filament\Resources\PegawaiResource\RelationManagers\PenggajianRelationManager;
use App\Models\Golongan;

class PegawaiResource extends Resource
{
    protected static ?string $model = Pegawai::class;

    protected static ?string $navigationLabel = 'Pegawai';
    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationGroup = 'Kepegawaian';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Select::make('golongan_id')
                            ->required()
                            ->options(Golongan::all()->pluck('nama', 'id')->toArray()),
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('nip')
                                    ->label('NIP')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('nik')
                                    ->label('NIK')
                                    ->required()
                                    ->maxLength(255),
                            ]),
                        Forms\Components\TextInput::make('nama')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Radio::make('jenis_kelamin')
                            ->required()
                            ->options([
                                'L' => 'Laki-laki',
                                'P' => 'Perempuan',
                            ]),
                    ])->columnSpan(6),

                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('tempat_lahir')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\DatePicker::make('tanggal_lahir')
                                    ->required(),
                            ]),
                        Forms\Components\TextInput::make('telepon')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('agama')
                            ->required()
                            ->options([
                                'islam' => 'Islam',
                                'kristen' => 'Kristen',
                                'katolik' => 'Katolik',
                                'hindu' => 'Hindu',
                                'buddha' => 'Buddha',
                                'konghucu' => 'Konghucu',
                            ]),
                        Forms\Components\Radio::make('status')
                            ->required()
                            ->options([
                                'menikah' => "Menikah",
                                'belum menikah' => "Belum Menikah",
                            ]),
                    ])->columnSpan(6),

                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Textarea::make('alamat')->required(),
                    ]),
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\FileUpload::make('foto')
                            ->required()
                            ->image(),
                    ]),
            ])->columns(12);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nip')->label('NIP')->searchable(),
                Tables\Columns\TextColumn::make('nama')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('jenis_kelamin')->label('Gender')->sortable(),
                Tables\Columns\TextColumn::make('tanggal_lahir')->date('d F Y'),
                Tables\Columns\TextColumn::make('telepon'),
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
            LemburRelationManager::class,
            CutiRelationManager::class,
            PenggajianRelationManager::class,
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPegawais::route('/'),
            'create' => Pages\CreatePegawai::route('/create'),
            'view' => Pages\ViewPegawai::route('/{record}'),
            'edit' => Pages\EditPegawai::route('/{record}/edit'),
        ];
    }    
}
