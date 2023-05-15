<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use PDF;
use Illuminate\Http\Request;

class PegawaiController extends Controller
{
    public function generatePDF()
    {
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

        // return $pdf->download('pegawai_report.pdf');
        return $pdf->stream();
        // return $view;
    }
}
