@extends('pdf.layout')

@section('tanggal')
    <p style="padding-bottom: 20px">Tanggal: <span style="font-weight: 500">{{ \Carbon\Carbon::parse($tanggalAwal)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($tanggalAkhir)->format('d/m/Y') }}</span></p>
@endsection

@section('tabel')
    <table>
        <caption style="font-size: 16px; font-weight: 400; text-align: center">Restok</caption>
        <thead>
            <tr>
                <th>TANGGAL</th>
                <th>PRODUK</th>
                <th>STOK</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $d)
                <tr>
                    <td class="text-capitalize text-center">{{ \Carbon\Carbon::parse($d->tanggal)->format('d-m-Y') }}</td>
                    <td class="text-capitalize text-center">{{ $d->nama }}</td>
                    <td class="text-capitalize text-center">{{ $d->stok }} unit</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="3" style="font-weight: 500">Total Unit: {{ $totalRestok }}</td>
            </tr>
            <tr>
                <td colspan="3" style="font-weight: 500">Total Pengeluaran: Rp{{ $totalModal }}</td>
            </tr>
        </tbody>
    </table>

    <div style="margin-bottom: 12px"></div>

    <table>
        <caption style="font-size: 16px; font-weight: 400; text-align: center">Penjualan</caption>
        <thead>
            <tr>
                <th>TANGGAL</th>
                <th>PRODUK</th>
                <th>STOK</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($stokKeluar as $d)
                <tr>
                    <td class="text-capitalize text-center">{{ \Carbon\Carbon::parse($d->tanggal_transaksi)->format('d-m-Y') }}</td>
                    <td class="text-capitalize text-center">{{ $d->namaProduk }}</td>
                    <td class="text-capitalize text-center">{{ $d->kuantitas }} unit</td>
                </tr>
            @endforeach
        </tbody>
        <tr>
            <td colspan="3" style="font-weight: 500">Total Unit: {{ $totalPenjualan }}</td>
        </tr>
        <tr>
            <td colspan="3" style="font-weight: 500">Total Pendapatan Bersih: Rp{{ $keuntungan }}</td>
        </tr>
    </table>
@endsection
