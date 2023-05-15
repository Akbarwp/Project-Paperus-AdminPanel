<?php

namespace App\Filament\Resources\ProdukResource\Pages;

use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\ProdukResource;

class ListProduks extends ListRecords
{
    protected static string $resource = ProdukResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
            // Actions\Action::make('Produk')
            //     ->color('secondary')
            //     ->icon('heroicon-o-document-report')
            //     ->url(route('produk.pdf'))
            //     ->openUrlInNewTab(),
            // Actions\Action::make('Stok')
            //     ->color('secondary')
            //     ->icon('heroicon-o-document-report')
            //     ->url(route('stok.pdf'))
            //     ->openUrlInNewTab(),
        ];
    }
}
