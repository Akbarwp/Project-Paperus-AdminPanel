<?php

namespace App\Filament\Resources\SalesDetailResource\Pages;

use App\Models\Sales;
use App\Models\Produk;
use App\Models\SalesDetail;
use Filament\Pages\Actions;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\SalesDetailResource;

class EditSalesDetail extends EditRecord
{
    protected static string $resource = SalesDetailResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $produk = Produk::find($data['produk_id']);
        $oldProduk = Produk::find($record['produk_id']);
        $oldSalesDetail = SalesDetail::find($record['id']);

        if ($data['produk_id'] != $record->produk_id) {
            
            $oldProduk->update([
                'stok' => $oldProduk->stok + $oldSalesDetail->kuantitas
            ]);
            $produk->update([
                'stok' => $produk->stok - $data['kuantitas']
            ]);

        } else if ($data['produk_id'] == $record->produk_id) {

            $produk->update([
                'stok' => $produk->stok + $oldSalesDetail->kuantitas
            ]);
            $produk->update([
                'stok' => $produk->stok - $data['kuantitas']
            ]);
        }

        $sales = Sales::find($record['sales_id']);
        $sales->update([
            'grand_total' => $sales->grand_total - $oldSalesDetail['total'],
        ]);
        $sales->update([
            'grand_total' => $sales->grand_total + $data['total'],
        ]);

        $sales = parent::handleRecordUpdate($record, $data);

        return $sales;
    }
}
