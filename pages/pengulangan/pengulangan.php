    <?php
    @$page = $_GET['aksi'];
    switch ($page) {
        case 'for':
            include "for.php";
            break;

        case 'while':
            include "while.php";
            break;

        case 'dowhile':
            include "dowhile.php";
            break;
        case 'foreach':
            include "foreach.php";
            break;
        default:
            include "tampil.php";
            break;
    }
    ?>

