@extends('layouts.dashboard')

@section('title', 'Daftar Pengajuan - ' . $pengaturan['app_name'])

@section('content')
<div class="card">
    <div class="card-header d-grid gap-3">
        <div class="d-flex justify-content-between align-items-center">
            <h3 class="m-0">Daftar Pengajuan Saya</h3>
            <a href="/pengajuan/tambah" class="btn btn-primary">Tambah</a>
        </div>

        <x-flash-message />
    </div>


    <div class="card-body">
        <table class="table table-bordered" id="data-pengajuan">
            <thead>
                <tr>
                    <th>Id Pengajuan</th>
                    <th>Nama Siswa</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {

        $('#data-pengajuan').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: "{{ route('pengajuan.index') }}",
            columns: [{
                    data: 'id_pengajuan',
                    name: 'id_pengajuan',
                },
                {
                    data: 'siswa.nama',
                    name: 'siswa.nama',
                },
                {
                    data: 'status',
                    name: 'status',
                    render: function(data, type, row) {
                        // Tentukan kelas CSS berdasarkan status
                        const status = data.toUpperCase();
                        let badgeClass = '';
                        switch (data.toLowerCase()) {
                            case 'disetujui':
                                badgeClass = 'bg-success';
                                break;
                            case 'ditolak':
                                badgeClass = 'bg-danger';
                                break;
                            default:
                                badgeClass = 'bg-warning';
                                break;
                        }

                        return `<span class="badge ${badgeClass}">${status}</span>`;
                    }
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
            const id = $(this).data('id');
            if (confirm('Yakin ingin menghapus data ini?')) {
                axios.delete('/pengajuan/hapus/' + id)
                    .then(function(response) {
                        alert(response.data.message);
                        $('#data-pengajuan').DataTable().ajax.reload();
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