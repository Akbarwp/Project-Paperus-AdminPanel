@extends('pdf.layout')

@section('tanggal')
    <p style="padding-bottom: 20px">Tanggal: <span style="font-weight: 500">{{ \Carbon\Carbon::now('Asia/Jakarta')->format('d/m/Y') }}</span></p>
@endsection

@section('tabel')
    <table>
        <thead>
            <tr>
                <th>NAMA</th>
                <th>MODAL</th>
                <th>HARGA</th>
                <th>STOK</th>
                <th>CREATED AT</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $d)
                <tr>
                    <td class="text-capitalize text-center">{{ $d->nama }}</td>
                    <td class="text-capitalize text-center">Rp{{ $d->modal }}</td>
                    <td class="text-capitalize text-center">Rp{{ $d->harga }}</td>
                    <td class="text-capitalize text-center">{{ $d->stok }} unit</td>
                    <td class="text-capitalize text-center">{{ \Carbon\Carbon::parse($d->created_at)->format('d-m-Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection