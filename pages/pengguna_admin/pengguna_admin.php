    <?php
    @$page = $_GET['aksi'];
    switch ($page) {
        case 'tampil':
            include "tampil.php";
            break;
        case 'tambah':
            include "tambah.php";
            break;
        case 'edit':
            include "edit.php";
            break;
        case 'view':
            include "view.php";
            break;
        case 'delete':
            include "delete.php";
            break;
        case 'proses_tambah':
            include "proses_tambah.php";
            break;
        case 'proses_delete':
            include "proses_delete.php";
            break;
        case 'laporan':
            include "../laporan/laporan_user.php";
            break;

        default:
            include "tampil.php";
            break;
    }
    ?>