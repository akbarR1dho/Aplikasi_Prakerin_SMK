<div class="modal fade" id="modalUbahPassword" tabindex="-1" aria-hidden="true">
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
                <form id="formUbahPassword">
                    @csrf
                    <div class="mb-3">
                        <label for="passwordSekarang" class="form-label">Password Sekarang</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text cursor-pointer togglePassword" data-target="passwordSekarang"><i class='bx  bx-eye-slash'></i></span>
                            <input
                                type="password"
                                id="passwordSekarang"
                                name="password_sekarang"
                                class="form-control"
                                placeholder="Masukkan Password Sekarang"
                                required />
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="passwordBaru" class="form-label">Password Baru</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text cursor-pointer togglePassword" data-target="passwordBaru"><i class='bx  bx-eye-slash'></i></span>
                            <input
                                type="password"
                                id="passwordBaru"
                                name="password_baru"
                                class="form-control"
                                placeholder="Masukkan Password Baru"
                                required />
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="konfirmasiPassword" class="form-label">Konfirmasi Password</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text cursor-pointer togglePassword" data-target="konfirmasiPassword"><i class='bx  bx-eye-slash'></i></span>
                            <input
                                type="password"
                                id="konfirmasiPassword"
                                name="konfirmasi_password"
                                class="form-control"
                                placeholder="Masukkan Konfirmasi Password"
                                required />
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
            </form>
        </div>
    </div>
</div>