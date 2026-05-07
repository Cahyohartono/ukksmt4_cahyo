<?php
// Cek apakah form telah disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tambahdata'])) {
    // Panggil function tambah_admin dengan mengirimkan $_POST dan $_FILES
    // Jalankan fungsi tambahAdmin dengan mengambil semua data teks form ($_POST) dan data file yang diunggah ($_FILES), kemudian simpan hasilnya (sukses/gagal) ke dalam variabel $result
    // $result: Variabel untuk menampung nilai balik (hasil) dari fungsi tambahAdmin. Biasanya berisi true (jika berhasil) atau false/pesan error (jika gagal)

    $result = tambah_admin($_POST, $_FILES);
    
    if ($result) {
        // Redirect jika berhasil
        echo "<script>
            alert('Data berhasil ditambahkan!');
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

// Ambil ID User Auto Number
$sql_tipe_user = "SELECT id_tipe_user FROM tbl_tipe_user WHERE tipe_user='Admin'";
$hasil = mysqli_query($koneksi, $sql_tipe_user);
$row = mysqli_fetch_assoc($hasil);
$id_user = autonumber("tbl_users", "id_user", 7, "ADM");

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
                <h3 class="card-title">Tambah Data Pengguna Admin</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form class="form-horizontal" method="post" enctype="multipart/form-data">
                <div class="card-body">
                    <div class="form-group row">
                        <label for="inputIdAdmin" class="col-sm-2 col-form-label">ID Admin</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="inputIdAdmin" name="id_admin" value="<?php echo autonumber("tbl_users", "id_user", 7, "ADM"); ?>" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="inputNamaAdmin" class="col-sm-2 col-form-label">Nama Admin</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="nama_admin" id="inputNamaAdmin" placeholder="Nama Admin">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="inputAlamatAdmin" class="col-sm-2 col-form-label">Alamat Admin</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" name="alamat_admin" id="inputAlamatAdmin" placeholder="Alamat Admin"></textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="inputNoTelp" class="col-sm-2 col-form-label">Nomor Telepon</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" id="inputNoTelp" name="telepon" placeholder="Nomor Telepon">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="inputJenkel" class="col-sm-2 col-form-label">Jenis Kelamin</label>
                        <div class="col-sm-10 mt-2">
                            <div class="form-check">
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" name="jenkel" id="inputJenkelL" value="L" checked>
                                    <label class="form-check-label" for="inputJenkelL">Laki-laki</label>
                                </div>  
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" name="jenkel" id="inputJenkelP" value="P">
                                    <label class="form-check-label" for="inputJenkelP">Perempuan</label>
                                </div>  
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="inputUsername" class="col-sm-2 col-form-label">Username</label>
                        <div class="col-sm-10">
                            <input type="email" class="form-control" id="inputUsername" name="email" placeholder="Username">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="inputPassword" class="col-sm-2 col-form-label">Password</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" id="inputPassword" name="password" placeholder="Password">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="inputConfirmPassword" class="col-sm-2 col-form-label">Confirm Password</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" id="inputConfirmPassword" name="password2" placeholder="Confirm Password">
                        </div>
                    </div>

                    <input type="text" name="role" value="Admin" hidden>


                    <div class="form-group row"></div>
                    <div class="form-group row">
                    <div class="offset-sm-2 col-sm-10">
                        
                        <button type="submit" class="btn btn-info" name="tambahdata">Tambah</button>
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