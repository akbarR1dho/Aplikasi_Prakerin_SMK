<div class="modal fade" id="modalGantiWalas" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Ganti Walas</h5>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formGantiWalas">
                    @csrf
                    <div class="d-grid">
                        <label for="walas" class="form-label">Walas</label>

                        <select id="walasSelect" class="form-control" required name="walas"></select>
                    </div>
                    <div id="currentWalas" class="mt-3">
                        <small class="text-muted">Walas saat ini: <strong></strong></small>
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