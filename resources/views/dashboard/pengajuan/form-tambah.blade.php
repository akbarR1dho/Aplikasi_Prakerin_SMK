@extends('layouts.dashboard')

@section('title', 'Form Tambah Pengajuan - ' . $pengaturan['app_name'])

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Tambah Pengajuan</h3>

        <x-flash-message />
    </div>

    <div class="card-body">
        <form action="{{ route('pengajuan.tambah') }}" method="POST" id="formPengajuan">
            @csrf
            <div class="mb-3">
                <label class="form-label" for="basic-icon-default-fullname">Nama Industri</label>
                <div class="input-group input-group-merge">
                    <span class="input-group-text"><i class='bx bx-building'></i></span>
                    <input
                        type="text"
                        class="form-control"
                        id="nama_industri"
                        placeholder="Masukkan Nama Industri"
                        name="nama_industri" value="{{ old('nama_industri') }}" />
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label" for="basic-icon-default-fullname">Kontak Industri</label>
                <div class="input-group input-group-merge">
                    <span class="input-group-text"><i class='bx bx-user-id-card'></i></span>
                    <input
                        type="text"
                        class="form-control"
                        id="kontak_industri"
                        placeholder="Masukkan Kontak Industri"
                        name="kontak_industri" value="{{ old('kontak_industri') }}" />
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label" for="basic-icon-default-fullname">Alamat Industri</label>
                <div class="input-group input-group-merge">
                    <span class="input-group-text"><i class='bx bx-map'></i></span>
                    <textarea
                        name="alamat_industri"
                        id="alamat_industri"
                        cols="30"
                        rows="4"
                        class="form-control"
                        placeholder="Masukkan Alamat Industri" required>{{ old('alamat_industri"') }}</textarea>
                </div>
            </div>
            <div class="d-flex gap-3">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('pengajuan.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        $('#formPengajuan').submit(function(e) {
            e.preventDefault();

            const isConfirmed = confirm('Yakin ingin menambahkan data ini?');
            if (isConfirmed) {
                this.submit();
            }
        });
    })
</script>
@endsection