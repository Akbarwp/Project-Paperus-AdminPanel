<?php

namespace App\Filament\Resources\SalesDetailResource\Pages;

use App\Models\Sales;
use App\Models\Produk;
use App\Models\SalesDetail;
use Filament\Pages\Actions;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\SalesDetailResource;

class CreateSalesDetail extends CreateRecord
{
    protected static string $resource = SalesDetailResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function handleRecordCreation(array $data): Model
    {
        $sales = parent::handleRecordCreation($data);

        $produk = Produk::find($sales['produk_id']);

        $hasil = Produk::find($sales['produk_id'])->update([
            'stok' => $produk->stok - $sales['kuantitas']
        ]);

        $salesTotal = Sales::find($data['sales_id']);
        $salesTotal->update([
            'grand_total' => $salesTotal['grand_total'] + $data['total'],
        ]);

        return $sales;
    }
}
