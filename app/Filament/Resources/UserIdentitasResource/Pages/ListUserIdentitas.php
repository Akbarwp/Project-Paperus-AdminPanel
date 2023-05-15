<?php

namespace App\Filament\Resources\UserIdentitasResource\Pages;

use App\Filament\Resources\UserIdentitasResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUserIdentitas extends ListRecords
{
    protected static string $resource = UserIdentitasResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
