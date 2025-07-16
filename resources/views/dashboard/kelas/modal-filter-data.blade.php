<!-- Modal Filter -->
<div class="modal fade" id="modalFilterData" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="filterModalLabel">Filter Data Kelas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div>
                    <div class="mb-3">
                        <input type="number" id="filter-angkatan" class="form-control" placeholder="Masukkan Angkatan">
                    </div>
                    <div class="mb-3">
                        <select id="filter-tingkat" class="form-control">
                            <option value="">Semua Tingkat</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                        </select>
                    </div>
                    <div>
                        <select id="filter-kelompok" class="form-control">
                            <option value="">Semua Kelompok</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="btnResetFilter">Reset Filter</button>
                <button type="button" class="btn btn-primary" id="btnTerapkanFilter">Terapkan Filter</button>
            </div>
        </div>
    </div>
</div>