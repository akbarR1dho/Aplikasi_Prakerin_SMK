@extends('layouts.dashboard')

@section('title', 'Daftar Jurusan - ' . $pengaturan['app_name'])

@section('content')
<div class="card">
    <div class="card-header d-grid gap-3">
        <div class="d-flex justify-content-between align-items-center">
            <h3 class="m-0">Daftar Jurusan</h3>
            <button
                type="button"
                id="btnTambah"
                class="btn btn-primary">
                Tambah
            </button>
        </div>

        <x-flash-message />
    </div>


    <div class="card-body">
        <table class="table table-bordered" id="data-jurusan">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Kode Jurusan</th>
                    <th>Kepala Program</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

@include('dashboard.jurusan.modal-form')

@endsection

@section('script')
<script>
    $(document).ready(function() {

        $('#data-jurusan').DataTable({
            processing: true,
            responsive: true,
            serverSide: true,
            ajax: "{{ route('jurusan.index') }}",
            columns: [{
                    data: 'nama',
                    name: 'nama'
                },
                {
                    data: 'kode_jurusan',
                    name: 'kode_jurusan'
                },
                {
                    data: 'kaprog.nama',
                    name: 'kaprog.nama',
                    defaultContent: '-',
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });

        select2Custom('#guruSelect', '/jurusan/load-kaprog', 'nama', 'Pilih Kaprog...', '#modalForm');

        $('#btnTambah').click(function() {
            $('#modalForm').modal('show');
            $('#modalTitle').text('Tambah Jurusan');
        });

        $(document).on('click', '#btnEdit', function() {
            let id = $(this).data('id'); // Ambil ID dari tombol yang diklik

            $('#modalForm').modal('show');
            $('#modalTitle').text('Edit Jurusan');

            // Ambil data guru
            axios.get('/jurusan/get-data/' + id)
                .then(response => {
                    let data = response.data;

                    $('#id').val(data.id);
                    $('#nama').val(data.nama);
                    $('#kodeJurusan').val(data.kode_jurusan);

                    // Tambahkan div baru dengan nama kaprog saat ini
                    const kaprogDiv = `
                    <div id="currentKaprog" class="mt-3">
                        <small class="text-muted">Kaprog saat ini: <strong>${data.kaprog.nama || 'Belum ditentukan'}</strong></small>
                    </div>
                `;

                    // Sisipkan di bawah #guruSelect
                    $('#guruSelect').parent().append(kaprogDiv);
                })
                .catch(error => {
                    alert(error.response.data.message);
                    console.error('Gagal mengambil data guru:', error);
                });
        });

        $('#modalForm').on('hidden.bs.modal', function() {
            $('#formJurusan')[0].reset();
            $('#guruSelect').val('').trigger('change');

            // Hapus div kaprog saat ini
            $('#currentKaprog').remove();
        });

        $('#formJurusan').submit(function(e) {
            e.preventDefault();
            const formData = $(this).serialize();

            const isConfirmed = confirm('Yakin ingin menyimpan data ini?');

            if (isConfirmed) {
                axios.post('{{ route("jurusan.simpan") }}', formData)
                    .then(response => {
                        alert(response.data.message);
                        $('#modalForm').modal('hide');
                        $('#data-jurusan').DataTable().ajax.reload();
                    })
                    .catch(error => {
                        alert(error.response.data.message);
                        console.error('Gagal menambahkan jurusan:', error);
                    });
            }
        });

        $(document).on('click', '#btnHapus', function() {
            const id = $(this).data('id');
            if (confirm('Yakin ingin menghapus data ini?')) {
                axios.delete('/jurusan/' + id)
                    .then(function(response) {
                        alert(response.data.message);
                        $('#data-guru').DataTable().ajax.reload();
                    })
                    .catch(function(error) {
                        console.log(error);
                        alert(error.response.data.message);
                    })
            }
        });
    });
</script>
@endsection