
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
            <h1>DataTables</h1>
            </div>
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Data Admin</li>
            </ol>
            </div>
        </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
        <div class="col-12">
            <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Pengguna | Admin</h3>
            </div>
            
            <!-- /.card-header -->
            <div class="card-body">
                <div class="col-md-6">
                    <a href="?pages=pengguna_admin&aksi=tambah" class="btn btn-sm btn-success">Tambah</a>
                    <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#modalTambahAdmin">
                        <i class="fas fa-plus"></i> Tambah Modal
                    </button>
                    <a href="?pages=pengguna_admin&aksi=laporan" class="btn btn-sm btn-secondary">Laporan</a>
                </div>
                <table id="example2" class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Photo</th>
                    <th>Telepon</th>
                    <th>Email</th>
                    <th>Alamat</th>
                    <th>Aksi</th>
                </tr>
                </thead>
                <tbody>
                    <?php

                        //Memanggil data user admin inner dengan tbl_users

                        $sql = "SELECT tbl_admin.*, tbl_users.*, tbl_tipe_user.* FROM tbl_admin 
                            LEFT JOIN tbl_users ON tbl_admin.id_user = tbl_users.id_user 
                            LEFT JOIN tbl_tipe_user ON tbl_users.role = tbl_tipe_user.id_tipe_user";
                        $tampil = tampil("$sql");
                        $no = 1;
                        foreach ($tampil as $user) :  //pengganti kurung kurawal buka :

                    ?>
                <tr>
                    <td><?php echo $no; ?></td>
                    <td>
                        <img src="<?php 
                            // Cek apakah ada foto di database dan file-nya benar-benar ada di folder
                            if (!empty($user['path_photo_admin']) && file_exists('../images/users/admin/' . $user['path_photo_admin'])) {
                                echo '../images/users/admin/' . $user['path_photo_admin'];
                            } else {
                                echo '../images/default-avatar.png';
                            }
                            ?>" alt="Photo" width="50" height="50" style="object-fit: cover; border-radius: 50%;">
                    </td>
                    <td><?php echo $user['nama_admin']; ?></td>
                    <td><?php echo $user['telepon_admin']; ?></td>
                    <td><?php echo $user['email']; ?></td>
                    <td><?php echo $user['alamat_admin']; ?></td>
                    <td>
                        <a href="?pages=pengguna_admin&aksi=edit&id=<?= $user['id_user']; ?>" class="btn btn-sm btn-primary">Edit</a>
                        <!-- Tombol Edit dengan data attribute -->
                        <button type="button" class="btn btn-sm btn-primary btn-edit" 
                            data-id="<?= $user['id_user']; ?>"
                            data-nama="<?= htmlspecialchars($user['nama_admin']); ?>"
                            data-alamat="<?= htmlspecialchars($user['alamat_admin']); ?>"
                            data-telepon="<?= $user['telepon_admin']; ?>"
                            data-jenkel="<?= $user['jenis_kelamin']; ?>"
                            data-email="<?= $user['email']; ?>"
                            data-foto="<?= $user['path_photo_admin']; ?>"
                            data-toggle="modal" 
                            data-target="#modalEditAdmin">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <a href="?pages=pengguna_admin&aksi=delete&id=<?= $user['id_user']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">Delete</a>
                    </td>
                </tr>
                    <?php
                        $no++;
                        endforeach;  //pengganti kurung kurawal tutup jika menggunakan :
                    ?>

                </tbody>
                <tfoot>
                <tr>
                    <th>No</th>
                    <th>Photo</th>
                    <th>Nama</th>
                    <th>Telepon</th>
                    <th>Email</th>
                    <th>Alamat</th>
                    <th>Aksi</th>
                </tr>
                </tfoot>
                </table>
            </div>
            <!-- /.card-body -->
            </div>
            <!-- /.card -->

            
            <!-- /.card -->
        </div>
        <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->


<!-- MODAL TAMBAH ADMIN -->
<div class="modal fade" id="modalTambahAdmin" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h4 class="modal-title">
                    <i class="fas fa-user-plus"></i> Tambah Data Pengguna Admin
                </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="formTambahAdmin" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>ID Admin</label>
                                <input type="text" class="form-control" name="id_admin" 
                                    value="<?php echo autonumber("tbl_users", "id_user", 7, "ADM"); ?>" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nama Admin <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nama_admin" required placeholder="Nama Admin">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Alamat Admin</label>
                                <textarea class="form-control" name="alamat_admin" rows="2" placeholder="Alamat Admin"></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nomor Telepon</label>
                                <input type="number" class="form-control" name="telepon" placeholder="Nomor Telepon">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label>Jenis Kelamin</label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" name="jenkel" value="L" checked>
                                    <label class="form-check-label">Laki-laki</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" name="jenkel" value="P">
                                    <label class="form-check-label">Perempuan</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Email (Username) <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" name="email" required placeholder="Email">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" name="password" required placeholder="Password">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Confirm Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" name="password2" required placeholder="Confirm Password">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Photo Admin</label>
                                <input type="file" class="form-control" name="photo" accept="image/*">
                                <small class="text-muted">Format: JPG, JPEG, PNG, BMP. Maksimal 1MB</small>
                            </div>
                        </div>
                    </div>
                    
                    <input type="hidden" name="role" value="Admin">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" name="tambah_modal" class="btn btn-success">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL EDIT ADMIN -->
<div class="modal fade" id="modalEditAdmin" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title">
                    <i class="fas fa-user-edit"></i> Edit Data Pengguna Admin
                </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="formEditAdmin" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>ID Admin</label>
                                <input type="text" class="form-control" name="id_admin" id="edit_id_admin" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nama Admin <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nama_admin" id="edit_nama_admin" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Alamat Admin</label>
                                <textarea class="form-control" name="alamat_admin" id="edit_alamat_admin" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nomor Telepon</label>
                                <input type="number" class="form-control" name="telepon" id="edit_telepon_admin">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label>Jenis Kelamin</label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" name="jenkel" id="edit_jenkel_l" value="L">
                                    <label class="form-check-label">Laki-laki</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" name="jenkel" id="edit_jenkel_p" value="P">
                                    <label class="form-check-label">Perempuan</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Email (Username)</label>
                                <input type="email" class="form-control" name="email" id="edit_email" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Photo Saat Ini</label>
                                <div>
                                    <img id="edit_current_photo" src="" alt="Current Photo" 
                                        style="width: 80px; height: 80px; object-fit: cover; border-radius: 50%;">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Ganti Photo</label>
                                <input type="file" class="form-control" name="photo" accept="image/*">
                                <small class="text-muted">Kosongkan jika tidak ingin mengganti photo</small>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-info">
                                <i class="fas fa-key"></i> Untuk mengganti password, silahkan klik tombol 
                                <button type="button" class="btn btn-sm btn-warning" onclick="showGantiPasswordModal()">
                                    Ganti Password
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <input type="hidden" name="role" value="Admin">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" name="edit_modal" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL GANTI PASSWORD (dari edit.php) -->
<div class="modal fade" id="modalGantiPassword" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h4 class="modal-title">
                    <i class="fas fa-key"></i> Ganti Password Admin
                </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="formGantiPassword" method="post">
                <div class="modal-body">
                    <input type="hidden" name="id_user_password" id="ganti_password_id">
                    
                    <div class="alert alert-info">
                        <strong>Admin:</strong> <span id="ganti_password_nama"></span>
                    </div>
                    
                    <div class="form-group">
                        <label>Password Lama <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="pass_lama" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Password Baru <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="pass_baru" required>
                        <small>Minimal 6 karakter</small>
                    </div>
                    
                    <div class="form-group">
                        <label>Konfirmasi Password Baru <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="pass_baru_confirm" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="ganti_password" class="btn btn-warning">
                        <i class="fas fa-save"></i> Ganti Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // Semua kode JavaScript berada di dalam event ini.
    // Artinya kode hanya berjalan setelah halaman dan semua elemen HTML selesai dimuat.
    
    // BAGIAN EDIT: ketika tombol Edit diklik, kita ambil data dari tombol
    // lalu isi form di modal edit supaya pengguna bisa langsung mengubah data.
    $(document).on('click', '.btn-edit', function() {
        var id = $(this).data('id');
        var nama = $(this).data('nama');
        var alamat = $(this).data('alamat');
        var telepon = $(this).data('telepon');
        var jenkel = $(this).data('jenkel');
        var email = $(this).data('email');
        var foto = $(this).data('foto');
        
        console.log('Edit clicked - ID:', id, 'Foto:', foto);
        
        $('#edit_id_admin').val(id);
        $('#edit_nama_admin').val(nama);
        $('#edit_alamat_admin').val(alamat);
        $('#edit_telepon_admin').val(telepon);
        $('#edit_email').val(email);
        
        // Set jenis kelamin
        $('#edit_jenkel_l').prop('checked', false);
        $('#edit_jenkel_p').prop('checked', false);
        if (jenkel == 'L') {
            $('#edit_jenkel_l').prop('checked', true);
        } else if (jenkel == 'P') {
            $('#edit_jenkel_p').prop('checked', true);
        }
        
        // Set photo
        if (foto && foto != '' && foto != 'null') {
            var fotoPath = '../images/users/admin/' + foto;
            // Test apakah file ada
            var img = new Image();
            img.onload = function() {
                $('#edit_current_photo').attr('src', fotoPath);
            };
            img.onerror = function() {
                $('#edit_current_photo').attr('src', '../images/default-avatar.png');
                console.log('Foto tidak ditemukan:', fotoPath);
            };
            img.src = fotoPath;
        } else {
            $('#edit_current_photo').attr('src', '../images/default-avatar.png');
        }
        
        // Simpan data untuk ganti password
        $('#ganti_password_id').val(id);
        $('#ganti_password_nama').text(nama);
    });
    
    // TAMBAH ADMIN dengan AJAX
    $('#formTambahAdmin').on('submit', function(e) {
        e.preventDefault();
        
        // Validasi password
        var password = $('input[name="password"]').val();
        var password2 = $('input[name="password2"]').val();
        
        if (password !== password2) {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: 'Password dan Confirm Password tidak sama!'
            });
            return false;
        }
        
        if (password.length < 6) {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: 'Password minimal 6 karakter!'
            });
            return false;
        }
        
        // Tampilkan loading
        Swal.fire({
            title: 'Menyimpan data...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        var formData = new FormData(this);
        // FormData digunakan untuk mengirim data form termasuk file upload.
        // Karena kita kirim data dengan AJAX, kita tambahkan sendiri flag action
        // agar proses_modal_admin.php tahu request ini untuk tambah data.
        formData.append('tambah_modal', '1');
        
        $.ajax({
            url: '../pages/pengguna_admin/proses_modal_admin.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                Swal.close();
                console.log('Response:', response);
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(function() {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: response.message
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.close();
                console.log('AJAX Error:', xhr.responseText);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Terjadi kesalahan: ' + error
                });
            }
        });
    });
    
    // EDIT ADMIN dengan AJAX
    $('#formEditAdmin').on('submit', function(e) {
        e.preventDefault();
        
        // Tampilkan loading
        Swal.fire({
            title: 'Mengupdate data...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        var formData = new FormData(this);
        // Gunakan FormData agar semua input dan file dikirim dengan benar.
        // Tambahkan flag action edit_modal agar backend tahu ini request edit.
        formData.append('edit_modal', '1');
        
        $.ajax({
            // Kirim data ke backend PHP untuk diproses sebagai edit admin
            url: '../pages/pengguna_admin/proses_modal_admin.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                Swal.close();
                console.log('Response:', response);
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(function() {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: response.message
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.close();
                console.log('AJAX Error:', xhr.responseText);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Terjadi kesalahan: ' + error
                });
            }
        });
    });
    
    // GANTI PASSWORD dengan AJAX
    $('#formGantiPassword').on('submit', function(e) {
        e.preventDefault();
        
        var pass_baru = $('input[name="pass_baru"]').val();
        var pass_baru_confirm = $('input[name="pass_baru_confirm"]').val();
        
        if (pass_baru !== pass_baru_confirm) {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: 'Password Baru dan Konfirmasi tidak sama!'
            });
            return false;
        }
        
        if (pass_baru.length < 6) {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: 'Password minimal 6 karakter!'
            });
            return false;
        }
        
        Swal.fire({
            title: 'Mengganti password...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        // Untuk ganti password, kita juga pakai FormData agar struktur data konsisten.
        // Flag ganti_password ditambahkan agar backend mengenali proses ini.
        var formData = new FormData(this);
        formData.append('ganti_password', '1');
        
        $.ajax({
            // Kirim data ke backend PHP untuk memproses ganti password
            url: '../pages/pengguna_admin/proses_modal_admin.php',
            type: 'POST',
            data: formData,
            // Ketika menggunakan FormData, kedua opsi ini harus dipakai:
            // contentType:false artinya jQuery tidak memberi header content-type default.
            // processData:false artinya jangan ubah data menjadi query string.
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                Swal.close();
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(function() {
                        $('#modalGantiPassword').modal('hide');
                        $('#formGantiPassword')[0].reset();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: response.message
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.close();
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Terjadi kesalahan: ' + error
                });
            }
        });
    });
});

function showGantiPasswordModal() {
    $('#modalGantiPassword').modal('show');
}

function showGantiPasswordModal() {
    $('#modalGantiPassword').modal('show');
}
    // Hapus data Admin
    function hapusAdmin(id, kodeAdmin, namaAdmin) {
        // Menampilkan konfirmasi dengan nama dan kode admin
        var pesan = "Apakah anda yakin akan menghapus user admin " + kodeAdmin + " " + namaAdmin + "?";
        
        if (confirm(pesan)) {
            window.location.href = "?pages=pengguna_admin&aksi=delete&id=" + id;
        }
    }
</script>