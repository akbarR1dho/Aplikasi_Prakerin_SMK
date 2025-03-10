@extends('layouts.dashboard')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Edit Guru</h3>

        <x-flash-message />
    </div>

    <div class="card-body">
        <form action="{{ route('akun-guru.edit', $guru->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label" for="basic-icon-default-fullname">NIP</label>
                <div class="input-group input-group-merge">
                    <span class="input-group-text"><i class='bx bx-id-card'></i></span>
                    <input
                        type="text"
                        class="form-control"
                        id="nip"
                        placeholder="Masukkan NIP"
                        name="nip" value="{{ $guru->nip }}" />
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label" for="basic-icon-default-fullname">Nama</label>
                <div class="input-group input-group-merge">
                    <span class="input-group-text"><i class='bx bx-user'></i></span>
                    <input
                        type="text"
                        class="form-control"
                        id="nama"
                        placeholder="Masukkan Nama"
                        name="nama" required value="{{ $guru->nama }}" />
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label" for="basic-icon-default-fullname">Email</label>
                <div class="input-group input-group-merge">
                    <span class="input-group-text"><i class='bx bx-envelope'></i></span>
                    <input
                        type="email"
                        class="form-control"
                        id="email"
                        placeholder="Masukkan Email"
                        name="email" required value="{{ $guru->email }}" />

                </div>
            </div>
            <div class="mb-3">
                <label class="form-label" for="basic-icon-default-fullname">No. Telp</label>
                <div class="input-group input-group-merge">
                    <span class="input-group-text"><i class='bx bx-phone'></i></span>
                    <input
                        type="text"
                        class="form-control"
                        id="no_telp"
                        placeholder="Masukkan No. Telp"
                        name="no_telp" required value="{{ $guru->no_telp }}" />

                </div>
            </div>
            <div class="mb-3">
                <label class="form-label" for="basic-icon-default-fullname">Jenis Kelamin</label>
                <div class="input-group input-group-merge">
                    <span class="input-group-text"><i class='bx bx-body'></i></span>
                    <select name="jenis_kelamin" id="jenis_kelamin" class="form-select" required>
                        <option selected disabled {{ empty($guru->jenis_kelamin) ? 'selected' : '' }}>Pilih...</option>
                        <option value="L" {{ $guru->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ $guru->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
            </div>
            <div class="d-flex gap-3">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('akun-guru.index') }}" class="btn btn-danger">Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection