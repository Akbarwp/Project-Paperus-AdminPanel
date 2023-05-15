<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use App\Models\SalesDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use PDF;

class SalesController extends Controller
{
    public function index(Request $request, Sales $sales)
    {
        $user = $request->user();

        if (!$user) {
            redirect('login');
        }

        $sales = Sales::query()
            ->join('sales_detail as sd', 'sd.sales_id', '=', 'sales.id')
            ->where('sales.id', '=', $sales->id)
            ->where('sales.user_id', '=', $user->id)
            ->where('sales.status', '=', 'checkout')
            ->get();

        return view('sales', []);
    }

    public function create(Request $request, Sales $sales)
    {
        $user = $request->user();

        $sales = Sales::where('user_id', $user->id)
            ->where('sales.id', '=', $sales->id)
            ->where('status', 'checkout')->first();

        $salesDetail = Sales::query()
            ->join('sales_detail as sd', 'sd.sales_id', '=', 'sales.id')
            ->where('sales.id', '=', $sales->id)
            ->where('sales.user_id', '=', $user->id)
            ->where('sales.status', '=', 'checkout')
            ->get();
        
        foreach ($salesDetail as $item) {
            SalesDetail::where('sales_id', $item->sales_id)->where('produk_id', $item->produk_id)->update([
                'kuantitas' => $request->kuantitas,
                'total' => $request->kuantitas * $item->harga
            ]);
        }
        
        $grandTotal = Sales::query()
            ->join('sales_detail as sd', 'sd.sales_id', '=', 'sales.id')
            ->where('sales.id', '=', $sales->id)
            ->where('sales.user_id', '=', $user->id)
            ->where('sales.status', '=', 'checkout')
            ->sum('total');

        $sales->update([
            'tanggal_transaksi' => Carbon::now(),
            'grand_total' => $grandTotal,
            'status' => 'baru'
        ]);

        return redirect('home');
    }

    public function generatePDF(Sales $record)
    {
        // dd($record);
        $data = Sales::query()
            ->join('users', 'users.id', '=', 'sales.user_id')
            ->select('sales.*', 'users.email')
            ->where('status', '=', 'selesai')
            ->orderBy('sales.tanggal_transaksi', 'asc')
            ->get();

        $totalSemua = Sales::query()
            ->where('status', '=', 'selesai')
            ->sum('grand_total');
        
        $view = view()->share('sales', $data);
        $pdf = PDF::loadView('pdf/sales', [
            'judul' => 'Sales',
            'judulTabel' => 'DAFTAR PENJUALAN',
            'data' => $data,
            'totalSemua' => $totalSemua,
            'footer' => 'Laporan daftar penjualan'
        ])
        ->setPaper('a4', 'landscape');

        // return $pdf->download('pegawai_report.pdf');
        return $pdf->stream();
        // return $view;
    }
}
