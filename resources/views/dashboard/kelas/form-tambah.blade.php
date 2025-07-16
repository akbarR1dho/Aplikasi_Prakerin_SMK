@extends('layouts.dashboard')

@section('title', 'Form Tambah Kelas - ' . $pengaturan['app_name'])

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Tambah Kelas</h3>

        <x-flash-message />
    </div>

    <div class="card-body">
        <form action="{{ route('kelas.tambah') }}" method="POST" id="formTambahKelas">
            @csrf
            <div class="mb-3">
                <label class="form-label" for="jurusan">Jurusan</label>
                <div class="d-flex">
                    <span class="input-group-text"><i class="bx bx-bookmark-star"></i></span>
                    <div class="d-flex align-items-center border px-1 py-0" style="height: 38.95px; width: 100%;">
                        <select id="jurusan" name="jurusan" class="form-select border-0" style="box-shadow: none;"></select>
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label" for="walas">Walas</label>
                <div class="d-flex">
                    <span class="input-group-text"><i class='bx bxs-user-check'></i></span>
                    <div class="d-flex align-items-center border px-1 py-0" style="height: 38.95px; width: 100%;">
                        <select name="walas" id="walas" class="form-select border-0" style="box-shadow: none;"></select>
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label" for="basic-icon-default-fullname">Angkatan</label>
                <div class="input-group input-group-merge">
                    <span class="input-group-text"><i class='bx bx-calendar-star'></i></span>
                    <input
                        type="number"
                        class="form-control"
                        id="angkatan"
                        placeholder="Masukkan Angkatan"
                        name="angkatan" value="{{ old('angkatan', date('Y')) }}" />

                </div>
            </div>
            <div class="mb-3">
                <label class="form-label" for="basic-icon-default-fullname">Tingkat</label>
                <div class="input-group input-group-merge">
                    <span class="input-group-text"><i class='bx bx-layers-alt'></i></span>
                    <select name="tingkat" id="tingkat" class="form-select">
                        <option selected disabled value>Pilih...</option>
                        <option value="11" @selected(old('tingkat')=='11' )>11</option>
                        <option value="12" @selected(old('tingkat')=='12' )>12</option>
                    </select>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label" for="basic-icon-default-fullname">Kelompok</label>
                <div class="input-group input-group-merge">
                    <span class="input-group-text"><i class='bx bx-tag'></i></span>
                    <select name="kelompok" id="kelompok" class="form-select">
                        <option selected disabled value>Pilih...</option>
                        <option value="A" @selected(old('kelompok')=='A' )>A</option>
                        <option value="B" @selected(old('kelompok')=='B' )>B</option>
                        <option value="C" @selected(old('kelompok')=='C' )>C</option>
                    </select>
                </div>
            </div>
            <div class="d-flex gap-3">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('kelas.index') }}" class="btn btn-danger">Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('js/select2-init-custom.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#formTambahKelas').submit(function(e) {
            e.preventDefault();

            const isConfirmed = confirm('Yakin ingin menambahkan data ini?');
            if (isConfirmed) {
                this.submit();
            }
        });

        select2Custom('#jurusan', '/kelas/load-jurusan');
        select2Custom('#walas', '/kelas/load-walas');
    })
</script>
@endsection