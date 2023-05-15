<?php

namespace App\Filament\Resources\RestockResource\Pages;

use App\Models\Produk;
use Filament\Pages\Actions;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\RestockResource;

class CreateRestock extends CreateRecord
{
    protected static string $resource = RestockResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function handleRecordCreation(array $data): Model
    {
        $stok = parent::handleRecordCreation($data);

        $produk = Produk::find($stok['produk_id']);

        $hasil = Produk::find($stok['produk_id'])->update([
            'stok' => $produk->stok + $stok['stok']
        ]);

        return $stok;
    }
}
