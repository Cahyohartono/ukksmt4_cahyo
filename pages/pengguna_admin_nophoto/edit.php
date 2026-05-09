<?php
// Cek apakah form telah disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['editdata'])) {
    // Panggil function edit_admin dengan mengirimkan $_POST dan $_FILES
    // Jalankan fungsi editAdmin dengan mengambil semua data teks form ($_POST) dan data file yang diunggah ($_FILES), kemudian simpan hasilnya (sukses/gagal) ke dalam variabel $result
    // $result: Variabel untuk menampung nilai balik (hasil) dari fungsi editAdmin. Biasanya berisi true (jika berhasil) atau false/pesan error (jika gagal)

    $result = edit_admin($_POST, $_FILES);
    
    if ($result) {
        // Redirect jika berhasil
        echo "<script>
            alert('Data berhasil diubah!');
            document.location.href = 'index.php?pages=admin';
        </script>";
        exit;
    } else {
        // Tampilkan error jika gagal (error sudah disimpan di session)
        if (isset($_SESSION['form_errors'])) {
            echo "<div class='alert alert-danger alert-dismissible'>
                    <button type='button' class='close' data-dismiss='alert'>&times;</button>
                    <ul>";
            foreach ($_SESSION['form_errors'] as $error) {
                echo "<li>$error</li>";
            }
            echo "</ul></div>";
            unset($_SESSION['form_errors']);
        }
    }
}

// Cek id role
$sql_tipe_user = "SELECT id_tipe_user FROM tbl_tipe_user WHERE tipe_user='Admin'";
$hasil = mysqli_query($koneksi, $sql_tipe_user);
$row = mysqli_fetch_assoc($hasil);


$id = $_GET['id'];
$sql = "SELECT tbl_admin.*, tbl_users.* FROM tbl_admin 
    JOIN tbl_users ON tbl_admin.id_user = tbl_users.id_user WHERE tbl_admin.id_user='$id' ";


$edit = mysqli_query($koneksi, $sql);
$data_admin = null;

while ($row = mysqli_fetch_assoc($edit)) {
    $data_admin = $row; // Simpan data ke variabel
    
    $id_user = $row['id_user'];
    $email = $row['email'];
    $password = $row['password'];
    $role = $row['role'];
    $nama = $row['nama_admin'];
    $alamat = $row['alamat_admin'];
    $jenkel = $row['jenis_kelamin'];
    $telp = $row['telepon_admin'];
    $foto = $row['path_photo_admin'];
}


// ==========================================
// PROSES GANTI PASSWORD (VIA MODAL)
// ==========================================
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ganti_password'])) {
    
    $result = ganti_password($_POST);
    
    if ($result) {
        echo "<script>
            alert('Password berhasil diubah!');
            $('#modalGantiPassword').modal('hide');
        </script>";
    } else {
        if (isset($_SESSION['form_errors'])) {
            echo "<script>alert('" . implode("\\n", $_SESSION['form_errors']) . "');</script>";
            unset($_SESSION['form_errors']);
        }
    }
}


/* Debugging tambahan untuk memastikan data alamat_admin benar-benar ada dan dapat diakses dengan benar. Gunakan $data_admin untuk debug

// Gunakan $data_admin untuk debug
 
if ($data_admin) {
    $debug_data = [
        'raw_alamat' => $data_admin['alamat_admin'],
        'type' => gettype($data_admin['alamat_admin']),
        'empty_check' => empty($data_admin['alamat_admin']),
        'null_check' => is_null($data_admin['alamat_admin']),
        'length' => strlen($data_admin['alamat_admin']),
        'ord_0' => ord($data_admin['alamat_admin'][0] ?? 'none'),
    ];
    
    echo "<pre>";
    print_r($debug_data);
    echo "</pre>";
}

*/

?>



<!-- <div class="content-wrapper" style="min-height: 1203.52px;"> -->
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
            <h1>Pengguna Admin</h1>
            </div>
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active"><a href="index.php?pages=pengguna_admin">Pengguna Admin</a></li>
            </ol>
            </div>
        </div>
        </div><!-- /.container-fluid -->
    </section>
        <!-- <?php
            if (isset($_POST['tambahdata'])) {
                include "proses_tambah.php";
        }
        ?> -->
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
            <!-- /.card -->
            <!-- Horizontal Form -->
            <div class="card card-info">
                <div class="card-header">
                <h3 class="card-title">Edit Data Pengguna Admin</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form class="form-horizontal" method="post" enctype="multipart/form-data">
                <div class="card-body">
                    <div class="form-group row">
                        <label for="inputIdAdmin" class="col-sm-2 col-form-label">ID Admin</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="inputIdAdmin" name="id_admin" value="<?= $id_user; ?>" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="inputNamaAdmin" class="col-sm-2 col-form-label">Nama Admin</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="nama_admin" id="inputNamaAdmin" placeholder="Nama Admin" value="<?= $nama; ?>">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="inputAlamatAdmin" class="col-sm-2 col-form-label">Alamat Admin</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" name="alamat_admin" id="inputAlamatAdmin" placeholder="Alamat Admin" ><?= $alamat; ?></textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="inputNoTelp" class="col-sm-2 col-form-label">Nomor Telepon</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" id="inputNoTelp" name="telepon" placeholder="Nomor Telepon" value="<?= $telp; ?>">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="inputJenkel" class="col-sm-2 col-form-label">Jenis Kelamin</label>
                        <div class="col-sm-10 mt-2">
                            <div class="form-check">
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" name="jenkel" id="inputJenkelL" value="L" <?= ($jenkel == 'L') ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="inputJenkelL">Laki-laki</label>
                                </div>  
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" name="jenkel" id="inputJenkelP" value="P" <?= ($jenkel == 'P') ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="inputJenkelP">Perempuan</label>
                                </div>  
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="inputUsername" class="col-sm-2 col-form-label">Username</label>
                        <div class="col-sm-10">
                            <input type="email" class="form-control" id="inputUsername" name="email" placeholder="Username" value="<?= $email; ?>" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="login-text text-center">
                            <p class="mt-3 text-black">Mau ganti password? 
                                <a href="#" class="text-primary" data-toggle="modal" data-target="#modalGantiPassword">Ganti Password</a> user admin !
                            </p>
                        </div>
                    </div>

                    <input type="text" name="role" value="Admin" hidden>

                    <div class="form-group row"></div>
                    <div class="form-group row">
                    <div class="offset-sm-2 col-sm-10">
                        
                        <button type="submit" class="btn btn-info" name="editdata">Edit</button>
                        <button type="submit" class="btn btn-danger">Hapus</button>
                        <button type="submit" class="btn btn-warning">Cancel</button>
                        
                    </div>
                    </div>
                </div>
                <!-- /.card-body -->
                
                <!-- /.card-footer -->
                </form>
            </div>
            <!-- /.card -->

            </div>
            <!--/.col (left) -->
        </div>
        <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
<!-- </div> -->

<!-- ========================================== -->
<!-- MODAL GANTI PASSWORD -->
<!-- ========================================== -->
<div class="modal fade" id="modalGantiPassword">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h4 class="modal-title">
                    <i class="fas fa-key"></i> Ganti Password Admin
                </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form method="post" id="formGantiPassword">
                <div class="modal-body">
                    <!-- ID USER yang tertangkap dari edit.php (hidden) -->
                    <input type="hidden" name="id_user_password" value="<?= $id_user; ?>">
                    <input type="hidden" name="action" value="ganti_password">

                    <!-- Informasi admin yang sedang diedit -->
                    <div class="alert alert-info">
                        <strong>Admin:</strong> <?= $nama; ?> (ID: <?= $id_user; ?>)
                    </div>

                    <!-- Password Lama -->
                    <div class="form-group">
                        <label>Password Lama <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="pass_lama" placeholder="Masukkan password lama" required>
                    </div>

                    <!-- Password Baru -->
                    <div class="form-group">
                        <label>Password Baru <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="pass_baru" placeholder="Minimal 6 karakter" required>
                        <small class="text-muted">Password minimal 6 karakter</small>
                    </div>

                    <!-- Konfirmasi Password Baru -->
                    <div class="form-group">
                        <label>Konfirmasi Password Baru <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="pass_baru_confirm" placeholder="Ulangi password baru" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" name="ganti_password" class="btn btn-warning">
                        <i class="fas fa-save"></i> Ganti Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JS SEDERHANA (Optional, untuk reset modal) -->
<script>
$(document).ready(function() {
    // Reset form saat modal ditutup
    $('#modalGantiPassword').on('hidden.bs.modal', function() {
        $('#formGantiPassword')[0].reset();
    });
});
</script>