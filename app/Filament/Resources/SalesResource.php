<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Sales;
use App\Models\Produk;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\SalesResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SalesResource\RelationManagers\SalesdetailRelationManager;
use App\Filament\Resources\SalesResource\Widgets\SalesOverview;
use Illuminate\Support\Carbon;

class SalesResource extends Resource
{
    protected static ?string $model = Sales::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard';
    protected static ?string $navigationGroup = 'Penjualan';
    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        // Forms\Components\TextInput::make('id')
                        //     ->name('Sales id')
                        //     ->default('SS'.Carbon::now()->format('dmY'))
                        //     ->disabled(),
                        Forms\Components\Select::make('user_id')
                            ->label('User')
                            ->required()
                            ->searchable()
                            ->options(User::all()->pluck('name', 'id')->toArray()),
                        Forms\Components\DateTimePicker::make('tanggal_transaksi')
                            ->required(),
                        Forms\Components\TextInput::make('grand_total')
                            ->numeric(),
                        Forms\Components\TextInput::make('biaya_kirim')
                            ->numeric(),
                    ])->columnSpan(8),

                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Select::make('metode_pembayaran')
                            ->required()
                            ->options([
                                'cod' => 'Bayar di Tempat',
                                'bank transfer' => 'Bank Transfer',
                                'e-wallet' => 'Pembayaran Virtual',
                            ]),
                        Forms\Components\Radio::make('status')
                            ->required()
                            ->options([
                                'baru' => 'Baru',
                                'proses' => 'Proses',
                                'kirim' => 'Kirim',
                                'batal' => 'Batal',
                                'selesai' => 'Selesai',
                            ])
                            ->descriptions([
                                'baru' => 'Pesanan baru',
                                'proses' => 'Pesanan diproses',
                                'kirim' => 'Pengirimaan pesanan',
                                'batal' => 'Pesanan dibatalkan',
                                'selesai' => 'Pesanan selesai',
                            ]),
                    ])->columnSpan(4),
            ])->columns(12);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.email')->searchable()->sortable()->limit(10),
                Tables\Columns\TextColumn::make('tanggal_transaksi')->dateTime('d F Y')->sortable(),
                Tables\Columns\TextColumn::make('grand_total')->sortable(),
                Tables\Columns\TextColumn::make('biaya_kirim'),
                Tables\Columns\TextColumn::make('metode_pembayaran'),
                Tables\Columns\BadgeColumn::make('status')->sortable()->searchable()
                    ->enum([
                        'baru' => 'Baru',
                        'proses' => 'Proses',
                        'kirim' => 'Kirim',
                        'batal' => 'Batal',
                        'selesai' => 'Selesai',
                    ])
                    ->colors([
                        'primary' => 'baru',
                        'secondary' => 'proses',
                        'warning' => 'kirim',
                        'danger' => 'batal',
                        'success' => 'selesai',
                    ]),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'checkout' => 'Checkout',
                        'baru' => 'Baru',
                        'proses' => 'Proses',
                        'kirim' => 'Kirim',
                        'batal' => 'Batal',
                        'selesai' => 'Selesai',
                    ]),
                Tables\Filters\Filter::make('tanggal_transaksi')
                    ->form([
                        Forms\Components\DatePicker::make('tanggal_awal'),
                        Forms\Components\DatePicker::make('tanggal_akhir'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['tanggal_awal'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tanggal_transaksi', '>=', $date),
                            )
                            ->when(
                                $data['tanggal_akhir'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tanggal_transaksi', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            SalesdetailRelationManager::class,
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSales::route('/'),
            'create' => Pages\CreateSales::route('/create'),
            'view' => Pages\ViewSales::route('/{record}'),
            'edit' => Pages\EditSales::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            SalesOverview::class,
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
            'User' => $record->user_id,
            'Tanggal' => $record->tanggal_transaksi,
            'Status' => $record->status,
        ];
    }
}
