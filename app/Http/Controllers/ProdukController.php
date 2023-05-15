<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use App\Models\Produk;
use App\Models\Kategori;
use PDF;
use App\Models\SalesDetail;
use Illuminate\Http\Request;
use App\Models\KategoriProduk;
use Illuminate\Support\Carbon;

class ProdukController extends Controller
{
    public function portofolio()
    {
        $produk = Produk::where('status', '=', 1)->paginate(12);

        return view('portofolio', [
            'produk' => $produk
        ]);
    }

    public function index()
    {
        $produk = Produk::where('status', '=', 1)->get();
        
        return view('produk', [
            'produk' => $produk
        ]);
    }

    public function byKategori(Kategori $kategori)
    {
        $produk = Produk::join('kategori_produk as kp', 'kp.produk_id', '=', 'produk.id')
            ->where('kp.kategori_id', '=', $kategori->id)
            ->where('status', '=', 1)
            ->orderBy('produk.created_at', 'desc')
            ->get();
        
        return view('post.index', [
            "produks" => $produk,
            "kategori" => $kategori,
        ]);
    }

    public function show(Produk $produk)
    {
        $kategori = KategoriProduk::where('produk_id', '=', $produk->id)->first();

        $rekomendasi = Produk::join('kategori_produk as kp', 'kp.produk_id', '=', 'produk.id')
            ->where('kp.kategori_id', '=', $kategori->kategori_id)
            ->where('status', '=', 1)
            ->where('produk.id', '!=', $produk->id)
            ->orderBy('produk.created_at', 'desc')
            ->paginate(4);
        
        return view('show', [
            'produk' => $produk,
            'rekomendasi' => $rekomendasi,
        ]);
    }

    public function cart(Request $request, Produk $produk)
    {
        $user = $request->user();

        $cek_cart = Sales::where('user_id', $user->id)
            ->where('status', 'checkout')->first();

        if (empty($cek_cart)) {
            Sales::create([
                'user_id' => $user->id,
                'tanggal_transaksi' => Carbon::now(),
                'grand_total' => $produk->harga,
                'status' => 'checkout'
            ]);
        }

        $sales = Sales::where('user_id', $user->id)
            ->where('status', 'checkout')->first();

        SalesDetail::create([
            'kuantitas' => 1,
            'total' => $produk->harga,
            'sales_id' => $sales->id,
            'produk_id' => $produk->id,
        ]);

        return redirect('sales');
    }

    public function generatePDFProduk()
    {
        $data = Produk::all();

        $view = view()->share('produk', $data);
        $pdf = PDF::loadView('pdf/produk', [
            'judul' => 'Produk',
            'judulTabel' => 'DAFTAR PRODUK',
            'data' => $data,
            'footer' => 'Laporan daftar produk'
        ])
        ->setPaper('a4', 'landscape');

        // return $pdf->download('pegawai_report.pdf');
        return $pdf->stream();
        // return $view;
    }

    public function generatePDFStok()
    {
        $data = Produk::query()
            ->join('restock as r', 'r.produk_id', '=', 'produk.id')
            ->select('r.*', 'produk.nama')
            ->get();

        $view = view()->share('stok', $data);
        $pdf = PDF::loadView('pdf/stok', [
            'judul' => 'Stok',
            'judulTabel' => 'DAFTAR STOK PRODUK',
            'data' => $data,
            'footer' => 'Laporan stok produk'
        ])
        ->setPaper('a4', 'landscape');

        // return $pdf->download('pegawai_report.pdf');
        return $pdf->stream();
        // return $view;
    }
}
