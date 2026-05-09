<?php
require_once "../inc/functions.php";

$id = $_GET['id'];


$sql = "SELECT tbl_admin.*, tbl_users.* FROM tbl_admin 
    JOIN tbl_users ON tbl_admin.id_user = tbl_users.id_user WHERE tbl_admin.id_user='$id' ";
$query = mysqli_query($koneksi, $sql) or die("Gagal melakukan query...!!!" . mysqli_error($KONEKSI));
$row = mysqli_fetch_assoc($query);
//echo $row['nama_admin'];
//die;

// Proses Hapus data admin ketika tombol hapus di klik
if (isset($_POST['hapus'])) {
    $result = hapus_admin($id);
    
    if ($result) {
        // Redirect jika berhasil
        echo "<script>
            alert('Data berhasil dihapus!');
            document.location.href = 'index.php?pages=pengguna_admin';
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


?>
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
                    <h3 class="card-title">Hapus Pengguna | Admin</h3>
                </div>
                
                <!-- /.card-header -->
                
                
                <div class="row">
                    <div class="col-md-12">
                        <form class="form-horizontal" method="post" enctype="multipart/form-data">
                            <!-- /.card-header -->
                            <div class="card-body">
                                <blockquote>
                                    <h1>Apakah anda yakin akan menghapus data Admin :</h1>
                                        <div>
                                            <span style="color:red; font-weight: bold">
                                                <?php echo $row['id_user']; ?> - 
                                                <?php echo $row['nama_admin']; ?>
                                        </div>
                                        </span>
                                    </h1>
                                    <button type="submit" class="btn btn-danger" name="hapus">Hapus Data</button>
                                    <a href="?pages=pengguna_admin" type="reset" class="btn btn-secondary" name="Hapus">Cancel</a>

                                </blockquote>
                            </div>
                            <!-- /.card-body -->
                        </form>
                    </div>
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
