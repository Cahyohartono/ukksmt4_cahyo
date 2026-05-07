
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
                            <input type="text" class="form-control" id="inputIdAdmin" name="id_admin" value="" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="inputNamaAdmin" class="col-sm-2 col-form-label">Nama Admin</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="nama_admin" id="inputNamaAdmin" placeholder="Nama Admin" value="">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="inputAlamatAdmin" class="col-sm-2 col-form-label">Alamat Admin</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" name="alamat_admin" id="inputAlamatAdmin" placeholder="Alamat Admin" ></textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="inputNoTelp" class="col-sm-2 col-form-label">Nomor Telepon</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" id="inputNoTelp" name="telepon" placeholder="Nomor Telepon" value="">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="inputJenkel" class="col-sm-2 col-form-label">Jenis Kelamin</label>
                        <div class="col-sm-10 mt-2">
                            <div class="form-check">
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" name="jenkel" id="inputJenkelL" value="L" >
                                    <label class="form-check-label" for="inputJenkelL">Laki-laki</label>
                                </div>  
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" name="jenkel" id="inputJenkelP" value="P" >
                                    <label class="form-check-label" for="inputJenkelP">Perempuan</label>
                                </div>  
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="inputUsername" class="col-sm-2 col-form-label">Username</label>
                        <div class="col-sm-10">
                            <input type="email" class="form-control" id="inputUsername" name="email" placeholder="Username" value="">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="inputUsername" class="col-sm-2 col-form-label">Photo Admin</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="exampleInputFile">
                                    <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                </div>
                                <div class="input-group-append">
                                    <span class="input-group-text" id="">Upload</span>
                                </div>
                            </div>
                            <div class="form-group row">
                            <div class="col-sm-2 mt-3">
                                <div class="card card-secondary card-outline">
                                    <div class="card-body box-profile">
                                        <div class="text-center">
                                            <img class="profile-user-img img-fluid img-circle" src="../../dist/img/user4-128x128.jpg" alt="User profile picture">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </div>
                        </div>

                        <div class="login-text text-center">
                            <p class="mt-3 text-black">Mau ganti password? <a href="../inc/forgot.php" class="">Ganti Password </a> user admin !</p>
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