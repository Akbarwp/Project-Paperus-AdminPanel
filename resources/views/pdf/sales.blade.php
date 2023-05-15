@extends('pdf.layout')

@section('tanggal')
    <p style="padding-bottom: 20px">Tanggal: <span style="font-weight: 500">{{ \Carbon\Carbon::parse($tanggalAwal)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($tanggalAkhir)->format('d/m/Y') }}</span></p>
@endsection

@section('tabel')
    <table>
        <thead>
            <tr>
                <th>id</th>
                <th>TANGGAL</th>
                <th>GRAND TOTAL</th>
                <th>BIAYA KIRIM</th>
                <th>USER</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $d)
                <tr>
                    <td class="text-capitalize text-center">{{ $d->id }}</td>
                    <td class="text-capitalize text-center">{{ \Carbon\Carbon::parse($d->tanggal_transaksi)->format('d-M-Y') }}</td>
                    <td class="text-capitalize text-center">Rp{{ $d->grand_total }}</td>
                    <td class="text-capitalize text-center">Rp{{ $d->biaya_kirim }}</td>
                    <td class="text-center">{{ $d->email }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="5" style="font-weight: 500">Laba Kotor: Rp{{ $labaKotor }}</td>
            </tr>
            <tr>
                <td colspan="5" style="font-weight: 500">Laba Bersih: Rp{{ $labaBersih }}</td>
            </tr>
        </tbody>
    </table>
@endsection