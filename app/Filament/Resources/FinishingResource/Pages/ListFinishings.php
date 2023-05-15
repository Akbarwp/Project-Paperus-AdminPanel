<?php

namespace App\Filament\Resources\FinishingResource\Pages;

use App\Filament\Resources\FinishingResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFinishings extends ListRecords
{
    protected static string $resource = FinishingResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
