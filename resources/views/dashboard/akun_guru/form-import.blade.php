@extends('layouts.dashboard')

@section('title', 'Form Import Guru - ' . $pengaturan['app_name'])

@section('content')
<div class="card">
    <div class="card-header">
        <h2>Import Guru</h2>

        <div class="my-3">
            <h4>Ketentuan File</h4>
            <ul>
                <li>File harus berformat .xlsx/.csv/.xls</li>
                <li>File tidak boleh lebih dari 2MB</li>
                <li>Baris nomor pertama harus berisi judul kolom</li>
                <li>
                    File harus memiliki kolom yang sesuai seperti dalam gambar
                    <br>
                    <div>
                        <!-- image untuk desktop -->
                        <img src="{{ asset('img/contoh-file-import-guru.png') }}" alt="contoh-file-import-guru" class="img-fluid d-none d-md-block">

                        <!-- image untuk mobile -->
                        <img src="{{ asset('img/contoh-file-import-guru.png') }}" alt="contoh-file-import-guru" class="img-fluid d-md-none" id="imgContohFileImportGuru">
                        <!-- <a href="{{ asset('img/contoh-file-import-guru.png') }}" data-lightbox="image-1" data-title="Contoh Template Excel">
                        </a> -->
                    </div>
                </li>
            </ul>
        </div>

        <x-flash-message />
    </div>

    <div class="card-body">
        <form action="{{ route('akun-guru.import') }}" method="POST" enctype="multipart/form-data" id="formImportGuru">
            @csrf
            <div class="mb-3">
                <label class="form-label" for="basic-icon-default-fullname">File Excel</label>
                <div class="input-group input-group-merge">
                    <span class="input-group-text"><i class='bx bx-file'></i></span>
                    <input
                        type="file"
                        class="form-control"
                        id="file"
                        placeholder="Masukkan File Excel"
                        name="file" required />
                </div>
            </div>
            <div class="d-flex gap-3">
                <button type="submit" class="btn btn-primary">Import</button>
                <a href="{{ route('akun-guru.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>

<div id="loading-overlay" style="display: none; position: fixed; top:0; left:0; width:100%; height:100%; background: rgba(255,255,255,0.8); z-index:9999; text-align: center;">
    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
        <div class="spinner-border spinner-border-lg text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p class="mt-3">Sedang mengimpor data...</p>
    </div>
</div>
@endsection

@section('script')
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.11.3/viewer.min.js"></script> -->
<script>
    $(document).ready(function() {
        $('#formImportGuru').submit(function(e) {
            e.preventDefault();

            const isConfirmed = confirm('Yakin ingin mengimpor data ini?');
            if (isConfirmed) {
                $('#loading-overlay').show();
                this.submit();
            }
        });

        const img = document.getElementById('imgContohFileImportGuru');
        const viewerjs = new Viewer(img, {
            inline: false, // jadi modal
            toolbar: {
                zoomIn: true,
                zoomOut: true
            },
            navbar: false,
        });
    });
</script>
@endsection