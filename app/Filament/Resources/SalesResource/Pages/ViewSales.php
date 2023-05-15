<?php

namespace App\Filament\Resources\SalesResource\Pages;

use App\Models\User;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\SalesResource;

class ViewSales extends ViewRecord
{
    protected static string $resource = SalesResource::class;

    protected function getActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
