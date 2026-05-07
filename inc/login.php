<?php
session_start();
require_once 'functions.php';

// Cek session
if (@$_SESSION['email']) {
    if (@$_SESSION['level'] == "Admin") {
        header("location:../admin/index.php");
    } elseif (@$_SESSION['level'] == "Petugas") {
        header("location:../petugas/index.php");
    } elseif (@$_SESSION['level'] == "Dokter") {
        header("location:../dokter/index.php");
    } elseif (@$_SESSION['level'] == "Pasien") {
        header("location:../pasien/index.php");
    } 
}


// Cek Login

// Jika tombol Signin (Login) ditekan , maka akan mengirim variabel yang ada form login yaitu username (email) dan password

if (isset($_POST['login'])) {
    $email = strtolower(stripslashes($_POST['email']));  // Email di input oleh user
    $userpass = $_POST['password']; //Password yang di input oleh user

    // Lalu kita query ke database
    $sql = mysqli_query($koneksi, "SELECT password, role FROM tbl_users WHERE email='$email' ");

    list($paswd, $role) = mysqli_fetch_array($sql); // kita ambil data password dan role hasil query dan simpan ke dalam variabel $paswd dan $role

    // Ambil level user dari role yang ada
    $type_user = "SELECT * FROM tbl_tipe_user WHERE id_tipe_user='$role' ";
    $hasil = mysqli_query($koneksi, $type_user);
    $row = mysqli_fetch_assoc($hasil);
    $level = $row['tipe_user'];

    // Jika data ditemukan dalam database, maka akan melakukan proses validasi dengan menggunakan password_verify

    if (mysqli_num_rows($sql) > 0) {
        /* jika ada data (>0) maka kita lakukan validasi
            $userpass ==> diambil dari form input yang dilakukan oleh user
            $passwd  ==> password yang ada di database dalam bentuk HASH
        */
        if (password_verify($userpass, $paswd)) {
            // Akan kita buat session 

            $_SESSION['email'] = $email;
            $_SESSION['level'] = $level;
            /*
            Jika berhasil login, maka user akan kita arahkan ke halaman admin sesuai dengan level user
            Jika dia level admin ===> admin/index.php
            Jika dia level petugas ===> petugas/index.php
            Jika dia level dokter ===> dokter/index.php

            */

            if ($_SESSION['level'] == "Admin") {
                header("location:../admin/index.php");
            } elseif ($_SESSION['level'] == "Petugas") {
                header("location:../petugas/index.php");
            } elseif ($_SESSION['level'] == "Dokter") {
                header("location:../dokter/index.php");
            }
            die();
        } else {
            echo '<script language="javascript">
            window.alert("LOGIN GAGAL...! Email/Password Salah");
            window.document.location.href="login.php";
            </script>';
        }
    } else {
        echo '<script language="javascript">
        window.alert("LOGIN GAGAL...! Email tidak ditemukan");
        window.document.location.href="login.php";
        </script>';
    }
}

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>AdminLTE 3 | Log in - Poliklinik Fix</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="../index2.html"><b>Login </b>Aplikasi Poliklinik</a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Sign in to start your session</p>

      <form  method="post">
        <div class="input-group mb-3">
          <input type="email" name="email" class="form-control" placeholder="Email">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" name="password" class="form-control" placeholder="Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="remember">
              <label for="remember">
                Remember Me
              </label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" name="login" class="btn btn-primary btn-block">Sign In</button>
          </div>
          <!-- /.col -->
        </div>
      </form>

      <!-- /.social-auth-links -->

      <p class="mb-1">
        <a href="#">I forgot my password</a>
      </p>
      <p class="mb-0">
        <a href="register.php" class="text-center">Register a new user</a>
      </p>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>

</body>
</html>
