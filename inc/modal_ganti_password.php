<!-- Modal Ganti Password Menggunakan validasi JS -->
<div class="modal fade" id="modalGantiPassword">
    <div class="modal-dialog modal-md">  <!-- Ubah modal-xl jadi modal-md (ukuran sedang) -->
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h4 class="modal-title">
                    <i class="fas fa-key mr-2"></i> Ganti Password
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="" id="formGantiPassword">
                <div class="modal-body">
                    <!-- ID User (hidden) untuk mengetahui user mana yang akan diganti passwordnya -->
                    <input type="hidden" name="id_user_password" value="<?= $id_user; ?>">
                    <input type="hidden" name="action" value="ganti_password">
                    
                    <!-- Password Lama -->
                    <div class="form-group">
                        <label for="inputIdPassLama">Password Lama <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            </div>
                            <input type="password" class="form-control" id="inputIdPassLama" name="pass_lama" placeholder="Masukkan password lama" required>
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="inputIdPassLama">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Password Baru -->
                    <div class="form-group">
                        <label for="inputPassBaru">Password Baru <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                            </div>
                            <input type="password" class="form-control" name="pass_baru" id="inputPassBaru" placeholder="Minimal 6 karakter" required>
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="inputPassBaru">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <small class="text-muted">Password minimal 6 karakter</small>
                    </div>
                          
                    <!-- Konfirmasi Password Baru -->
                    <div class="form-group">
                        <label for="inputConfirmPassBaru">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-check-circle"></i></span>
                            </div>
                            <input type="password" class="form-control" name="pass_baru_confirm" id="inputConfirmPassBaru" placeholder="Ulangi password baru" required>
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="inputConfirmPassBaru">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Password Strength Indicator (opsional) -->
                    <div class="form-group" id="password-strength" style="display: none;">
                        <div class="progress" style="height: 5px;">
                            <div class="progress-bar" id="strength-bar" role="progressbar" style="width: 0%;"></div>
                        </div>
                        <small id="strength-text" class="form-text"></small>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" name="ganti_password" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save changes
                    </button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
<!-- /.modal-dialog -->
</div>