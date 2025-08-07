@extends('layouts.dashboard')

@section('title', 'Form Import Siswa - ' . $pengaturan['app_name'])

@section('content')
<div class="card">
    <div class="card-header">
        <h2>Import Siswa</h2>

        <div class="my-3">
            <p>Tolong unduh template excel terlebih dahulu sebelum mengimpor data</p>
            <button
                type="button"
                id="btnDownloadTemplate"
                data-url="{{ route('download-template', ['nama_file' => 'template_siswa.xlsx']) }}"
                class="btn btn-primary">Unduh Template Excel
            </button>
        </div>

        <x-flash-message />
    </div>

    <div class="card-body">
        <form action="{{ route('akun-siswa.import') }}" method="POST" enctype="multipart/form-data" id="formImportSiswa">
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
                <a href="{{ route('akun-siswa.index') }}" class="btn btn-secondary">Kembali</a>
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
<script>
    $(document).ready(function() {
        $('#formImportSiswa').submit(function(e) {
            e.preventDefault();

            const isConfirmed = confirm('Yakin ingin mengimpor data ini?');
            if (isConfirmed) {
                $('#loading-overlay').show();
                this.submit();
            }
        });

        $('#btnDownloadTemplate').click(function() {
            const isConfirmed = confirm('Yakin ingin mengunduh template ini?');
            if (isConfirmed) {
                const url = $(this).data('url');
                window.location.href = url;
            }
        });
    });
</script>
@endsection