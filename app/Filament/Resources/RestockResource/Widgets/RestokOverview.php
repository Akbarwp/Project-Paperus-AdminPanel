<?php

namespace App\Filament\Resources\RestockResource\Widgets;

use App\Models\Produk;
use App\Models\Restock;
use Illuminate\Support\Carbon;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class RestokOverview extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    
    protected function getCards(): array
    {
        $now = Carbon::now('Asia/Jakarta');
        $tanggalAwal = Carbon::create($now->format('Y'), $now->format('m'), 1)->toDateString();
        $tanggalAkhir = Carbon::create($now->format('Y'), $now->format('m'), 31)->toDateString();

        $restok = Produk::query()
            ->join('restock as r', 'r.produk_id', '=', 'produk.id')
            ->select('r.*', 'produk.nama', 'produk.modal')
            ->whereDate('r.tanggal', '>=', $tanggalAwal)
            ->whereDate('r.tanggal', '<=', $tanggalAkhir)
            ->get();
        
        $totalModal = 0;
        foreach ($restok as $item) {
            $totalModal += $item->modal * $item->stok;
        }

        return [
            Card::make('Pengeluaran bulan ' . Carbon::now('Asia/Jakarta')->format('F'), "Rp" . $totalModal),
            Card::make('Jumlah Produk bulan ' . Carbon::now('Asia/Jakarta')->format('F'), 
                Restock::query()
                    ->whereDate('tanggal', '>=', $tanggalAwal)
                    ->whereDate('tanggal', '<=', $tanggalAkhir)
                    ->count('id')
            ),
            Card::make('Jumlah Restok bulan ' . Carbon::now('Asia/Jakarta')->format('F'), 
                Restock::query()
                    ->whereDate('tanggal', '>=', $tanggalAwal)
                    ->whereDate('tanggal', '<=', $tanggalAkhir)
                    ->sum('stok') . ' unit'
            ),
        ];
    }
}
