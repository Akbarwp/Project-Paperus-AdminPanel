<?php

namespace App\Filament\Resources\ProdukResource\Pages;

use App\Filament\Resources\ProdukResource;
use App\Filament\Resources\ProdukResource\Widgets\ProdukOverview;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewProduk extends ViewRecord
{
    protected static string $resource = ProdukResource::class;

    protected function getActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
