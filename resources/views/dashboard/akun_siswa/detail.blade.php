@extends('layouts.dashboard')

@section('title', 'Detail Siswa - ' . $pengaturan['app_name'])

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Informasi Siswa</h4>
    </div>
    <div class="card-body">
        <table class="table table-bordered mb-3">
            <tr>
                <th>Nama</th>
                <td>{{ $siswa->nama }}</td>
            </tr>
            <tr>
                <th>Tempat/Tgl. Lahir</th>
                <td>{{ $siswa->tempat_lahir . ', ' . $siswa->tanggal_lahir }}</td>
            </tr>
            <tr>
                <th>Jenis Kelamin</th>
                <td>{{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
            </tr>
            <tr>
                <th>Email</th>
                <td>{{ $siswa->akun->email }}</td>
            </tr>
            <tr>
                <th>No. Telepon</th>
                <td>{{ $siswa->no_telp }}</td>
            </tr>
            <tr>
                <th>Username Akun</th>
                <td>{{ $siswa->akun->username }}</td>
            </tr>
            <tr>
                <th>NIS</th>
                <td>{{ $siswa->nis }}</td>
            </tr>
            <tr>
                <th>NISN</th>
                <td>{{ $siswa->nisn }}</td>
            </tr>
            <tr>
                <th>Tahun Masuk</th>
                <td>{{ $siswa->tahun_masuk }}</td>
            </tr>
            <tr>
                <th>ID Kelas</th>
                <td>{{ $siswa->kelas->id_kelas }}</td>
            </tr>
            <tr>
                <th>Alamat</th>
                <td>{!! nl2br($siswa->alamat) !!}</td>
            </tr>
        </table>

        <a href="{{ URL::previous() !== URL::current() ? URL::previous() : route('akun-siswa.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>
@endsection