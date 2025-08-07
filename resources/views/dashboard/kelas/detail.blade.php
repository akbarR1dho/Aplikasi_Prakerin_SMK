@extends('layouts.dashboard')

@section('title', 'Detail Kelas - ' . $pengaturan['app_name'])

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Informasi Kelas</h4>
    </div>
    <div class="card-body">
        <div class="container mb-3">
            <table class="table table-bordered mb-5">
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

            <div>
                <h5>Daftar Siswa di Kelas</h5>
                <table class="table table-bordered" id="data-siswa">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th class="text-start">NIS</th>
                            <th class="text-start">NISN</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>

        <a href="/kelas" class="btn btn-secondary">Kembali</a>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {

        $('#data-siswa').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: "{{ route('kelas.data-siswa', $data->id) }}",
            columns: [{
                    data: 'nama',
                    name: 'nama',
                },
                {
                    data: 'nis',
                    name: 'nis',
                    class: 'text-start',
                    defaultContent: '-'
                },
                {
                    data: 'nisn',
                    name: 'nisn',
                    class: 'text-start',
                    defaultContent: '-'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });

        $(document).on('click', '#btnHapus', function() {
            const nis = $(this).data('id');

            if (confirm('Yakin ingin menghapus data ini?') == true) {
                axios.delete('/akun-siswa/hapus/' + nis)
                    .then(function(response) {
                        alert(response.data.message);
                        $('#data-siswa').DataTable().ajax.reload();
                    })
                    .catch(function(error) {
                        console.log(error);
                        alert(error.response.data.message);
                    })
            }
        });

        $(document).on('click', '.btnResetPassword', function() {
            let nis = $(this).data('id');

            isConfirmed = confirm('Yakin ingin mereset password?');

            if (isConfirmed) {
                axios.post('/akun-siswa/reset-password/' + nis)
                    .then(function(response) {
                        alert(response.data.message);
                        $('#data-siswa').DataTable().ajax.reload();
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