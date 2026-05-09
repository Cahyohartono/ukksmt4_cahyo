<?php
// Cek id role
$sql_tipe_user = "SELECT id_tipe_user FROM tbl_tipe_user WHERE tipe_user='Admin'";
$hasil = mysqli_query($koneksi, $sql_tipe_user);
$row_tipe = mysqli_fetch_assoc($hasil);

$id = $_GET['id'];
$sql = "SELECT tbl_admin.*, tbl_users.* FROM tbl_admin 
    JOIN tbl_users ON tbl_admin.id_user = tbl_users.id_user 
    WHERE tbl_admin.id_user='$id'";

echo "<h3>Query SQL:</h3>";
echo $sql . "<br><br>";

$edit = mysqli_query($koneksi, $sql);

if (!$edit) {
    die("Error query: " . mysqli_error($koneksi));
}

if (mysqli_num_rows($edit) == 0) {
    die("Data tidak ditemukan untuk ID: $id");
}

// Ambil data
$row = mysqli_fetch_assoc($edit);

// TAMPILKAN SEMUA ISI ROW
echo "<h3>Isi semua field dari database:</h3>";
echo "<pre>";
var_dump($row);
echo "</pre>";

// Tampilkan spesifik alamat
echo "<h3>Field alamat_admin:</h3>";
echo "Isi: '" . $row['alamat_admin'] . "'<br>";
echo "Panjang: " . strlen($row['alamat_admin']) . "<br>";
echo "Apakah null? " . (is_null($row['alamat_admin']) ? 'Ya' : 'Tidak') . "<br>";
echo "Apakah empty? " . (empty($row['alamat_admin']) ? 'Ya' : 'Tidak') . "<br>";
echo "ASCII pertama: " . ord($row['alamat_admin'][0]) . "<br>";

// Assign ke variabel
$id_user = $row['id_user'];
$email = $row['email'];
$password = $row['password'];
$role = $row['role'];
$nama = $row['nama_admin'];
$alamat = $row['alamat_admin'];
$jenkel = $row['jenis_kelamin'];
$telp = $row['telepon_admin'];
$foto = $row['path_photo_admin'];

echo "<h3>Data yang ditampilkan:</h3>";
echo "Nama: $nama<br>";
echo "Alamat: $alamat<br>";
echo "Telepon: $telp<br>";
echo "Jenis Kelamin: $jenkel<br>";
?>