<div class="modal fade" id="modalForm" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle"></h5>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formJurusan">
                    @csrf
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama</label>
                        <input
                            type="text"
                            id="nama"
                            name="nama"
                            class="form-control"
                            placeholder="Masukkan Nama"
                            required />
                    </div>
                    <div class="mb-3">
                        <label for="kode_jurusan" class="form-label">Kode Jurusan</label>
                        <input
                            type="text"
                            id="kodeJurusan"
                            name="kode_jurusan"
                            class="form-control"
                            placeholder="Masukkan Kode Jurusan"
                            maxlength="3"
                            required />
                    </div>
                    <div class="d-grid">
                        <label for="kaprog" class="form-label">Pilih Kaprog</label>

                        <select id="guruSelect" class="form-control" required name="id_kaprog"></select>
                    </div>
                    <input type="hidden" name="id" id="id">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    Close
                </button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
            </form>
        </div>
    </div>
</div>