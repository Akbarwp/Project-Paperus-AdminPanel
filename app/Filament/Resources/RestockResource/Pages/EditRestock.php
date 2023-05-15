<?php

namespace App\Filament\Resources\RestockResource\Pages;

use App\Models\Produk;
use Filament\Pages\Actions;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\RestockResource;

class EditRestock extends EditRecord
{
    protected static string $resource = RestockResource::class;

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

        if ($data['produk_id'] != $record->produk_id) {
            
            $oldProduk->update([
                'stok' => $oldProduk->stok - $record->stok,
            ]);
            $produk->update([
                'stok' => $produk->stok + $data['stok']
            ]);

        } else if ($data['produk_id'] == $record->produk_id) {

            $produk->update([
                'stok' => $produk->stok - $record->stok,
            ]);
            $produk->update([
                'stok' => $produk->stok + $data['stok']
            ]);
        }

        $restock = parent::handleRecordUpdate($record, $data);

        return $restock;
    }
}
