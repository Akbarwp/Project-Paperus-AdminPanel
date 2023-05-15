@extends('laporan.layout')

@section('container')
    <div class="w-full h-screen bg-gradient-to-br from-cyan-500 to-pink-500 flex justify-center items-center">
        <div class="px-5 py-3 bg-white/30 backdrop-blur-sm rounded-md">
            <h1 class="mb-3 font-bold text-2xl">Form Generate Laporan</h1>
            <form method="post" target="_blank">
                @csrf
                <div class="mb-3">
                    <label for="laporan" class="mb-2 font-medium text-base block">Jenis Laporan:</label>
                    <select class="rounded-lg" name="laporan" id="laporan" required>
                        <option disabled selected>Pilih Jenis Laporan</option>
                        <option value="produk">Produk</option>
                        <option value="stok">Stok Produk</option>
                        <option value="sales">Penjualan</option>
                        <option value="pegawai">Kepegawaian</option>
                    </select>
                </div>

                <div class="mb-3 inline-flex">
                    <div class="mr-5">
                        <label for="tanggalAwal" class="mb-2 font-medium text-base block">Tanggal Awal:</label>
                        <input type="date" class="rounded-lg" name="tanggalAwal" id="tanggalAwal" required value="{{ \Carbon\Carbon::now('Asia/Jakarta')->format('Y-m-d') }}">
                    </div>

                    <div>
                        <label for="tanggalAkhir" class="mb-2 font-medium text-base block">Tanggal Akhir:</label>
                        <input type="date" class="rounded-lg" name="tanggalAkhir" id="tanggalAkhir" required value="{{ \Carbon\Carbon::now('Asia/Jakarta')->format('Y-m-d') }}">
                    </div>
                </div>

                <button type="submit" class="w-full px-3 py-2 rounded-md bg-gradient-to-tl from-indigo-500 to-pink-500 font-semibold text-white">Generate</button>
            </form>
        </div>
    </div>
@endsection