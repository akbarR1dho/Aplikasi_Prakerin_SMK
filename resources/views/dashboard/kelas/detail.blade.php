@extends('layouts.dashboard')

@section('title', 'Detail Kelas - ' . $pengaturan['app_name'])

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Informasi Kelas</h4>
    </div>
    <div class="card-body">
        <table class="table table-bordered mb-3">
            <tr>
                <th>Jurusan</th>
                <td>{{ $data->jurusan->nama }}</td>
            </tr>
            <tr>
                <th>Walas</th>
                <td>{{ $data->walas->nama }}</td>
            </tr>
            <tr>
                <th>Tingkat</th>
                <td>{{ $data->tingkat }}</td>
            </tr>
            <tr>
                <th>Kelompok</th>
                <td>{{ $data->kelompok }}</td>
            </tr>
            <tr>
                <th>Angkatan</th>
                <td>{{ $data->angkatan }}</td>
            </tr>
            <tr>
                <th>Id Kelas</th>
                <td>{{ $data->id_kelas }}</td>
            </tr>
        </table>

        <a href="/kelas" class="btn btn-secondary">Kembali</a>
    </div>
</div>
@endsection