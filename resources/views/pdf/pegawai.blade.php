@extends('pdf.layout')

@section('tanggal')
    <p style="padding-bottom: 20px">Tanggal: <span style="font-weight: 500">{{ \Carbon\Carbon::now('Asia/Jakarta')->format('d/m/Y') }}</span></p>
@endsection

@section('tabel')
    <table>
        <thead>
            <tr>
                <th>NIP</th>
        <th>NAMA</th>
        <th>GENDER</th>
        <th>TTL</th>
        <th>TELEPON</th>
        <th>AGAMA</th>
        <th>STATUS</th>
        <th>GOLONGAN</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $d)
                <tr>
                    <td class="text-capitalize">{{ $d->nip }}</td>
                    <td class="text-capitalize">{{ $d->nama }}</td>
                    @if ($d->jenis_kelamin == 'L')
                        <td class="text-capitalize">Laki-laki</td>
                    @else
                        <td class="text-capitalize">Perempuan</td>
                    @endif
                    <td class="text-capitalize">{{ $d->tempat_lahir }}, {{ \Carbon\Carbon::parse($d->tanggal_lahir)->format('d-m-Y') }}</td>
                    <td class="text-capitalize">{{ $d->telepon }}</td>
                    <td class="text-capitalize">{{ $d->agama }}</td>
                    <td class="text-capitalize">{{ $d->status }}</td>
                    <td class="text-capitalize">{{ $d->golongan }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection