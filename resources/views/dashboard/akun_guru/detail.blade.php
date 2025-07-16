@extends('layouts.dashboard')

@section('title', 'Detail Guru - ' . $pengaturan['app_name'])

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Informasi Guru</h4>
    </div>
    <div class="card-body">
        <table class="table table-bordered mb-3">
            <tr>
                <th>Nama</th>
                <td>{{ $guru->nama }}</td>
            </tr>
            <tr>
                <th>Jenis Kelamin</th>
                <td>{{ $guru->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
            </tr>
            <tr>
                <th>Email</th>
                <td>{{ $guru->akun->email }}</td>
            </tr>
            <tr>
                <th>No. Telepon</th>
                <td>{{ $guru->no_telp }}</td>
            </tr>
            <tr>
                <th>Username Akun</th>
                <td>{{ $guru->akun->username }}</td>
            </tr>
            <tr>
                <th>NIP</th>
                <td>{{ $guru->nip ? $guru->nip : '-' }}</td>
            </tr>

            @if($guru->roles->isNotEmpty())
            <tr>
                <th>Role Tambahan</th>
                <td>
                    @foreach ($guru->roles as $role)
                    <span class="badge bg-primary">{{ $role->nama }}</span>
                    @endforeach
                </td>
            </tr>
            @endif
        </table>

        <a href="{{ route('akun-guru.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>
@endsection