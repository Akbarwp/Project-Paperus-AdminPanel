<?php

namespace App\Filament\Resources\SalesResource\Widgets;

use App\Models\Sales;
use Illuminate\Support\Facades\DB;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Illuminate\Support\Carbon;

class SalesOverview extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    
    protected function getCards(): array
    {
        $now = Carbon::now('Asia/Jakarta');
        $tanggalAwal = Carbon::create($now->format('Y'), $now->format('m'), 1)->toDateString();
        $tanggalAkhir = Carbon::create($now->format('Y'), $now->format('m'), 31)->toDateString();

        return [
            Card::make('Laba Kotor bulan ' . Carbon::now('Asia/Jakarta')->format('F'), "Rp" . Sales::query()
                ->where('status', '=', 'selesai')
                ->whereDate('tanggal_transaksi', '>=', $tanggalAwal)
                ->whereDate('tanggal_transaksi', '<=', $tanggalAkhir)
                ->sum('grand_total')
            ),
            Card::make('Laba Bersih bulan ' . Carbon::now('Asia/Jakarta')->format('F'), "Rp" . Sales::query()
                ->join('sales_detail as sd', 'sd.sales_id', '=', 'sales.id')
                ->whereDate('tanggal_transaksi', '>=', $tanggalAwal)
                ->whereDate('tanggal_transaksi', '<=', $tanggalAkhir)
                ->where('status', '=', 'selesai')
                ->sum('sd.keuntungan')
            ),
            Card::make('Jumlah Transaksi bulan ' . Carbon::now('Asia/Jakarta')->format('F'), 
                Sales::query()
                    ->where('status', '=', 'selesai')
                    ->whereDate('tanggal_transaksi', '>=', $tanggalAwal)
                    ->whereDate('tanggal_transaksi', '<=', $tanggalAkhir)
                    ->count('id')
            ),
            Card::make('Pesanan Baru', Sales::where('status', '!=', 'selesai')->where('status', '!=', 'keranjang')->count('id')),
        ];
    }
}
