<?php

namespace App\Filament\Resources\UserIdentitasResource\Pages;

use App\Filament\Resources\UserIdentitasResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUserIdentitas extends CreateRecord
{
    protected static string $resource = UserIdentitasResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
