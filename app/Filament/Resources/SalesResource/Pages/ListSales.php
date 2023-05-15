<?php

namespace App\Filament\Resources\SalesResource\Pages;

use Filament\Pages\Actions;
use App\Filament\Resources\SalesResource;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\SalesResource\Widgets\SalesOverview;

class ListSales extends ListRecords
{
    protected static string $resource = SalesResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
            // Actions\Action::make('Print')
                // ->color('secondary')
                // ->icon('heroicon-o-document-report')
                // ->url(route('sales.pdf'))
                // ->openUrlInNewTab(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            SalesOverview::class,
        ];
    }
}
