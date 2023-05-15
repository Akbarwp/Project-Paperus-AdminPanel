<?php

namespace App\Filament\Resources\FinishingResource\Pages;

use App\Filament\Resources\FinishingResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFinishing extends EditRecord
{
    protected static string $resource = FinishingResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
