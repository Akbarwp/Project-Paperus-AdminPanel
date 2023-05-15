<?php

namespace App\Http\Controllers;

use PDF;
use App\Models\Sales;
use App\Models\Produk;
use App\Models\Pegawai;
use App\Models\Restock;
use App\Models\SalesDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index()
    {
        return view('laporan.index');
    }

    public function generatePDF(Request $request)
    {
        //* Laporan Produk
        if ($request->laporan == 'produk') {
            $data = Produk::all();

            $view = view()->share('produk', $data);
            $pdf = PDF::loadView('pdf/produk', [
                'judul' => 'Produk',
                'judulTabel' => 'DAFTAR PRODUK',
                'data' => $data,
                'footer' => 'Laporan daftar produk'
            ])
            ->setPaper('a4', 'landscape');
            return $pdf->stream();

        //* Laporan Penjualan
        } elseif ($request->laporan == 'sales') {
            $data = Sales::query()
                ->join('users', 'users.id', '=', 'sales.user_id')
                ->select('sales.*', 'users.email')
                ->where('status', '=', 'selesai')
                ->whereDate('tanggal_transaksi', '>=', $request->tanggalAwal)
                ->whereDate('tanggal_transaksi', '<=', $request->tanggalAkhir)
                ->orderBy('sales.tanggal_transaksi', 'asc')
                ->get();

            $labaKotor = Sales::query()
                ->where('status', '=', 'selesai')
                ->whereDate('tanggal_transaksi', '>=', $request->tanggalAwal)
                ->whereDate('tanggal_transaksi', '<=', $request->tanggalAkhir)
                ->sum('grand_total');

            $labaBersih = Sales::query()
                ->join('sales_detail as sd', 'sd.sales_id', '=', 'sales.id')
                ->where('status', '=', 'selesai')
                ->whereDate('tanggal_transaksi', '>=', $request->tanggalAwal)
                ->whereDate('tanggal_transaksi', '<=', $request->tanggalAkhir)
                ->sum('sd.keuntungan');
            
            $view = view()->share('sales', $data);
            $pdf = PDF::loadView('pdf/sales', [
                'judul' => 'Sales',
                'judulTabel' => 'DAFTAR PENJUALAN',
                'data' => $data,
                'labaKotor' => $labaKotor,
                'labaBersih' => $labaBersih,
                'tanggalAwal' => $request->tanggalAwal,
                'tanggalAkhir' => $request->tanggalAkhir,
                'footer' => 'Laporan daftar penjualan'
            ])
            ->setPaper('a4', 'landscape');
            return $pdf->stream();

        //* Laporan Stok
        } elseif ($request->laporan == 'stok') {
            
            //* Stok Masuk
            $data = Produk::query()
                ->join('restock as r', 'r.produk_id', '=', 'produk.id')
                ->select('r.*', 'produk.nama', 'produk.modal')
                ->whereDate('r.tanggal', '>=', $request->tanggalAwal)
                ->whereDate('r.tanggal', '<=', $request->tanggalAkhir)
                ->orderBy('r.tanggal', 'asc')
                ->get();
            
            $totalRestok = Restock::query()
                ->whereDate('tanggal', '>=', $request->tanggalAwal)
                ->whereDate('tanggal', '<=', $request->tanggalAkhir)
                ->sum('stok');
            
            $totalModal = 0;
            foreach ($data as $item) {
                $totalModal += $item->modal * $item->stok;
            }
            
            //* Stok keluar
            $stokKeluar = Sales::query()
                ->join('sales_detail as sd', 'sd.sales_id', '=', 'sales.id')
                ->join('produk as p', 'p.id', '=', 'sd.produk_id')
                ->select('sales.*', 'sd.*', 'p.nama as namaProduk')
                ->where('sales.status', '=', 'selesai')
                ->whereDate('sales.tanggal_transaksi', '>=', $request->tanggalAwal)
                ->whereDate('sales.tanggal_transaksi', '<=', $request->tanggalAkhir)
                ->orderBy('sales.tanggal_transaksi', 'asc')
                ->get();

            $totalPenjualan = Sales::query()
                ->join('sales_detail as sd', 'sd.sales_id', '=', 'sales.id')
                ->where('sales.status', '=', 'selesai')
                ->whereDate('sales.tanggal_transaksi', '>=', $request->tanggalAwal)
                ->whereDate('sales.tanggal_transaksi', '<=', $request->tanggalAkhir)
                ->sum('sd.kuantitas');

            $keuntungan = Sales::query()
                ->join('sales_detail as sd', 'sd.sales_id', '=', 'sales.id')
                ->where('status', '=', 'selesai')
                ->whereDate('tanggal_transaksi', '>=', $request->tanggalAwal)
                ->whereDate('tanggal_transaksi', '<=', $request->tanggalAkhir)
                ->sum('sd.keuntungan');

            $view = view()->share('stok', $data);
            $pdf = PDF::loadView('pdf/stok', [
                'judul' => 'Stok',
                'judulTabel' => 'DAFTAR STOK PRODUK',
                'data' => $data,
                'totalRestok' => $totalRestok,
                'totalModal' => $totalModal,

                'stokKeluar' => $stokKeluar,
                'totalPenjualan' => $totalPenjualan,
                'keuntungan' => $keuntungan,

                'tanggalAwal' => $request->tanggalAwal,
                'tanggalAkhir' => $request->tanggalAkhir,
                'footer' => 'Laporan stok produk'
            ])
            ->setPaper('a4', 'landscape');
            return $pdf->stream();

        //* Laporan Kepegawaian
        } elseif($request->laporan == 'pegawai') {
            $data = Pegawai::query()
            ->join('golongan as g', 'g.id', '=', 'pegawai.golongan_id')
            ->select('pegawai.*', 'g.nama as golongan')
            ->get();

            $view = view()->share('pegawai', $data);
            $pdf = PDF::loadView('pdf/pegawai', [
                'judul' => 'Pegawai',
                'judulTabel' => 'DAFTAR PEGAWAI',
                'data' => $data,
                'footer' => 'Laporan daftar pegawai'
            ])
            ->setPaper('a4', 'landscape');
            return $pdf->stream();
        
        }
    }
}
