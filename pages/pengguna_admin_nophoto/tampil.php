
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
                    <a href="?pages=pengguna_admin&aksi=laporan" class="btn btn-sm btn-secondary">Laporan</a>
                </div>
                <table id="example2" class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
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
                    <td><?php echo $user['nama_admin']; ?></td>
                    <td><?php echo $user['telepon_admin']; ?></td>
                    <td><?php echo $user['email']; ?></td>
                    <td><?php echo $user['alamat_admin']; ?></td>
                    <td>
                        <a href="?pages=pengguna_admin&aksi=edit&id=<?= $user['id_user']; ?>" class="btn btn-sm btn-primary">Edit</a>
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
<script>
    // Hapus data Admin
    function hapusAdmin(id, kodeAdmin, namaAdmin) {
        // Menampilkan konfirmasi dengan nama dan kode admin
        var pesan = "Apakah anda yakin akan menghapus user admin " + kodeAdmin + " " + namaAdmin + "?";
        
        if (confirm(pesan)) {
            window.location.href = "?pages=pengguna_admin&aksi=delete&id=" + id;
        }
    }
</script>