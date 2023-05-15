<?php

namespace App\Filament\Resources\RestockResource\Pages;

use App\Filament\Resources\RestockResource;
use App\Filament\Resources\RestockResource\Widgets\RestokOverview;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRestocks extends ListRecords
{
    protected static string $resource = RestockResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            RestokOverview::class,
        ];
    }
}
