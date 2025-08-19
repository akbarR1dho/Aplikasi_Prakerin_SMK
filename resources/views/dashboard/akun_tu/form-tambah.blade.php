@extends('layouts.dashboard')

@section('title', 'Form Tambah Tu - ' . $pengaturan['app_name'])

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Tambah Tu</h3>

        <x-flash-message />
    </div>

    <div class="card-body">
        <form action="{{ route('akun-tu.tambah') }}" method="POST" id="formTambahTu">
            @csrf
            <div class="mb-3">
                <label class="form-label" for="basic-icon-default-fullname">NIP</label>
                <div class="input-group input-group-merge">
                    <span class="input-group-text"><i class='bx bx-user-id-card'></i></span>
                    <input
                        type="text"
                        class="form-control"
                        id="nip"
                        placeholder="Masukkan NIP"
                        name="nip" value="{{ old('nip') }}" />
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
                        name="nama" value="{{ old('nama') }}" />
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label" for="basic-icon-default-fullname">Email</label>
                <div class="input-group input-group-merge">
                    <span class="input-group-text"><i class='bx bx-envelope-alt'></i></span>
                    <input
                        type="email"
                        class="form-control"
                        id="email"
                        placeholder="Masukkan Email"
                        name="email" value="{{ old('email') }}" />

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
                        name="no_telp" value="{{ old('no_telp') }}" />

                </div>
            </div>
            <div class="mb-3">
                <label class="form-label" for="basic-icon-default-fullname">Jenis Kelamin</label>
                <div class="input-group input-group-merge">
                    <span class="input-group-text"><i class='bx bx-man-woman'></i></span>
                    <select name="jenis_kelamin" id="jenis_kelamin" class="form-select">
                        <option selected disabled value>Pilih...</option>
                        <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
            </div>
            <div class="d-flex gap-3">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('akun-tu.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        $('#formTambahTu').submit(function(e) {
            e.preventDefault();

            const isConfirmed = confirm('Yakin ingin menambahkan data ini?');
            if (isConfirmed) {
                this.submit();
            }
        });
    })
</script>
@endsection