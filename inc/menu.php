<?php
@$pages = $_GET['pages'];

switch ($pages) {
case 'dashboard':
        include "../pages/master/dashboard.php";
        break;
case 'login':
        include "../pages/master/login.php";
        break;
case 'register':
        include "../pages/master/register.php";
        break;
case 'tabel':
        include "../pages/master/tabel.php";
        break;
case 'form':
        include "../pages/master/form.php";
        break;
case 'form_edit':
        include "../pages/master/form_edit.php";
        break;
case 'invoice':
        include "../pages/master/invoice.php";
        break;


case 'pengguna_admin':
        include "../pages/pengguna_admin/pengguna_admin.php";
        break;

case 'pengguna_kasir':
        include "../pages/pengguna_kasir/pengguna_kasir.php";
        break;

case 'pengguna_dokter':
        include "../pages/pengguna_dokter/pengguna_dokter.php";
        break;

case 'pengulangan':
        include "../pages/pengulangan/pengulangan.php";
        break;

    default:
        include "../pages/master/dashboard.php";
        break;
}
