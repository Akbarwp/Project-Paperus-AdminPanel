<?php

namespace App\Filament\Resources\BahanResource\Pages;

use App\Filament\Resources\BahanResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBahan extends CreateRecord
{
    protected static string $resource = BahanResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
