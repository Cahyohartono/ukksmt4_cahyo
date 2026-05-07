<?php
// ===============================================================
// File: inc/function.php
// Description: File ini berisi fungsi-fungsi yang digunakan dalam aplikasi.
// Fungsi-fungsi ini dapat digunakan untuk berbagai keperluan, seperti validasi data, manipulasi string, atau operasi lainnya yang sering digunakan dalam aplikasi.
// ===============================================================  

// ===============================================================
// 1. Pengaturan Dasar
// ===============================================================

// Set zona waktu default
date_default_timezone_set('Asia/Jakarta');
$waktu_sekarang = date('Y-m-d H:i:s');


// ===============================================================
// 2. Koneksi Database
// ===============================================================

// Mnenggunakan mysqli untuk koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "poliklinik_fix";

// Membuat koneksi ke database
// $koneksi_db adalah variabel yang menyimpan hasil koneksi ke database, yang dapat digunakan untuk melakukan operasi database selanjutnya.

$koneksi = mysqli_connect($servername, $username, $password, $dbname);

// Cek koneksi apakah berhasil atau tidak
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}   

// ===============================================================
// 3. Fungsi Autonumber
// ===============================================================

function autonumber($tabel, $kolom, $lebar = 0, $awalan)
{
    global $koneksi;

    //proses auto number



    $auto = mysqli_query($koneksi, "select $kolom from $tabel order by $kolom desc limit 1") or die(mysqli_error($koneksi));

    $jumlah_record = mysqli_num_rows($auto);
    if ($jumlah_record == 0)
        $nomor = 1;

    else {
        $row = mysqli_fetch_array($auto);
        $nomor = intval(substr($row[0], strlen($awalan))) + 1;
    }
    if ($lebar > 0)
        $angka = $awalan . str_pad($nomor, $lebar, "0", STR_PAD_LEFT);
    else
        $angka = $awalan . $nomor;
    return $angka;
}
// echo autonumber("tbl_dokter","kode_dokter",3,"DOK");

// ===============================================================
// 4. Fungsi Registrasi
// ===============================================================

/**
 * Fungsi untuk mendaftarkan user baru ke database
 * 
 * @param array $data_form Data dari form registrasi ($_POST)
 * @return bool true jika berhasil, false jika gagal
 * 
 * PROSES DALAM FUNGSI INI:
 * 1. Mengambil data dari form
 * 2. Validasi (cek email duplikat, password cocok)
 * 3. Enkripsi password dengan password_hash()
 * 4. Simpan ke tabel tbl_users
 * 5. Simpan ke tabel tbl_admin
 */

function registrasi($data)
{
    // Mengambil koneksi database dan variabel tanggal dari luar fungsi
    global $koneksi;
    global $tgl;

    // 1. AMBIL DAN BERSIHKAN DATA DARI FORM
    // stripslashes: membuang karakter backslash (\) yang tidak perlu
    $id_user = stripslashes($data["id_user"]); 
    $nama    = stripslashes($data["nama"]); 
    
    // strtolower: paksa email jadi huruf kecil semua biar konsisten
    $email   = strtolower(stripslashes($data["email"])); 
    
    // mysqli_real_escape_string: pengaman agar karakter aneh/simbol tidak merusak query (cegah SQL Injection)
    $password  = mysqli_real_escape_string($koneksi, $data["password"]);
    $confirm_password = mysqli_real_escape_string($koneksi, $data["confirm_password"]);
    
    // 2. CEK APAKAH EMAIL SUDAH TERPAKAI?
    // Kita tanya ke database: "Ada tidak email yang sama di tabel user?"
    $result = mysqli_query($koneksi, "SELECT email from tbl_users WHERE email='$email'");

    // Jika hasil pencarian (fetch) ketemu, berarti email sudah ada yang punya
    if (mysqli_fetch_assoc($result)) {
        echo "<script>
        alert('Waduh! Email ini sudah terdaftar. Pakai email lain ya!');
        </script>";
        return false; // Berhenti di sini, jangan lanjut daftar
    }

    // 3. CEK KECOCOKAN PASSWORD
    // Bandingkan input password pertama dengan konfirmasi password kedua
    if ($password !== $confirm_password) {
        echo "<script>
        alert('Password dan konfirmasinya tidak sama! Coba cek lagi.');
        document.location.href='register.php';
        </script>";
        return false; // Berhenti, suruh user isi ulang
    }

    // 4. "BUNGKUS" PASSWORD BIAR AMAN (ENKRIPSI)
    // Jangan simpan password asli! Kita ubah jadi kode acak yang sulit ditebak
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // 5. CARI TAHU "ID" UNTUK ROLE ADMIN
    // Kita ambil ID spesifik untuk level 'Admin' dari tabel tipe user
    $tipe_user = "SELECT * FROM tbl_tipe_user WHERE tipe_user='Admin' ";
    $hasil = mysqli_query($koneksi, $tipe_user);
    $row = mysqli_fetch_assoc($hasil);
    $id = $row['id_tipe_user'];

    // 6. PROSES SIMPAN KE DATABASE (2 TABEL)
    
    // Simpan data login (email & password) ke tabel users
    $sql_user = "INSERT INTO tbl_users SET 
                id_user   = '$id_user',
                role      = '$id',
                email     = '$email',
                password  = '$password_hash',
                create_at = '$tgl' ";

    mysqli_query($koneksi, $sql_user) or die("Gagal simpan di tabel user: " . mysqli_error($koneksi));

    // Simpan data profil (nama) ke tabel admin
    $sql_admin = "INSERT INTO tbl_admin SET 
                    nama_admin = '$nama',
                    id_user    = '$id_user',
                    create_at  = '$tgl' ";

    mysqli_query($koneksi, $sql_admin) or die("Gagal simpan di tabel admin: " . mysqli_error($koneksi));

    // 7. SELESAI & LEMPAR KE HALAMAN LOGIN
    echo "<script>
    alert('Selamat! Akun Admin berhasil dibuat.');
    document.location.href='login.php';
    </script>";

    // Mengembalikan angka 1 jika ada data yang berhasil masuk
    return mysqli_affected_rows($koneksi);
}


// Membuat fungsi tampil \\
// ===================== \\
function tampil($DATA)
{
    global $koneksi; //variable yang ada di dalam scope asli berbeda dengan yang ada di luar, agar variabel di luar bisa dibaca dalam scope maka gunakan global

    $HASIL = mysqli_query($koneksi, $DATA);
    $data = []; // menyiapkan variabel/wadah yang masih kosongh untuk nanatinya akan kita gunakan untuk menyimpan data yang kita query/panggil dari database.

    while ($row = mysqli_fetch_assoc($HASIL)) {
        $data[] = $row; // kita masukan datanya disini
    }
    return $data; // kita kembalikan nilainya, di munculkan
}


// ================================ //
// Fungsi untuk proses tambah Admin \\
// ================================ \\
function tambah_admin($DATA, $FILES)
{
    global $koneksi;
    global $tgl;

    // 1. Ambil dan bersihkan data dari form
        $id_admin = htmlspecialchars(trim($DATA['id_admin'] ?? ''));
        $nama_admin = htmlspecialchars(trim($DATA['nama_admin'] ?? ''));
        $alamat_admin = htmlspecialchars(trim($DATA['alamat_admin'] ?? ''));
        $telepon = htmlspecialchars(trim($DATA['telepon'] ?? ''));
        $jenkel = htmlspecialchars(trim($DATA['jenkel'] ?? ''));
        $email = strtolower(htmlspecialchars(trim($DATA['email'] ?? '')));
        $password = $DATA['password'] ?? '';
        $password2 = $DATA['password2'] ?? '';
        $role = htmlspecialchars(trim($DATA['role'] ?? ''));

    // 2. Validasi form kosong
    $errors = [];
    
    if (empty($id_admin)) {
        $errors[] = "ID Admin tidak boleh kosong";
    }
    if (empty($nama_admin)) {
        $errors[] = "Nama Admin tidak boleh kosong";
    }
    if (empty($alamat_admin)) {
        $errors[] = "Alamat Admin tidak boleh kosong";
    }
    if (empty($telepon)) {
        $errors[] = "Telepon tidak boleh kosong";
    }
    if (empty($jenkel)) {
        $errors[] = "Jenis Kelamin tidak boleh kosong";
    }
    if (empty($email)) {
        $errors[] = "Email tidak boleh kosong";
    }
    if (empty($password)) {
        $errors[] = "Password tidak boleh kosong";
    }
    if (empty($password2)) {
        $errors[] = "Konfirmasi Password tidak boleh kosong";
    }
    if (empty($role)) {
        $errors[] = "Role tidak boleh kosong";
    }
    if (empty($telepon)) {
        $errors[] = "Telepon tidak boleh kosong";
    }

    // Jika ada error form kosong
    if (!empty($errors)) {
        $_SESSION['form_errors'] = $errors;
        return false;
    }

    // 3. Validasi email sudah terdaftar
    $result = mysqli_query($koneksi, "SELECT email FROM tbl_users WHERE email = '$email'");
    if (mysqli_fetch_assoc($result)) {
        $_SESSION['form_errors'] = ['Email yang diinput sudah terdaftar di database!'];
        return false;
    }

    // 4. Validasi konfirmasi password
    if ($password !== $password2) {
        $_SESSION['form_errors'] = ['Konfirmasi Password tidak sesuai!'];
        return false;
    }

    // 5. Validasi minimal panjang password (opsional)
    if (strlen($password) < 6) {
        $_SESSION['form_errors'] = ['Password minimal 6 karakter!'];
        return false;
    }

    // 6. Upload file foto
 //    $gambar_foto = upload_file_new($DATA, $FILES, '../images/users/');
    
       // upload_file_new sudah menampilkan alert, kita hanya perlu return false
    
    //   if (!$gambar_foto) {
    //    return false;
    // } 

    // 7. Enkripsi password
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // 8. Mulai transaksi database (opsional, untuk keamanan)
    mysqli_begin_transaction($koneksi);

    try {
        // Insert ke tbl_users
        $sql_user = "INSERT INTO tbl_users SET 
            id_user = '$id_admin',
            email = '$email',
            password = '$password_hash',
            role = (SELECT id_tipe_user FROM tbl_tipe_user WHERE tipe_user = 'Admin'),
            created_at = '$tgl'";

        if (!mysqli_query($koneksi, $sql_user)) {
            throw new Exception("Gagal menambahkan user: " . mysqli_error($koneksi));
        }

        // Insert ke tbl_admin
        $sql_admin = "INSERT INTO tbl_admin SET 
            nama_admin = '$nama_admin',
            alamat_admin = '$alamat_admin',
            telepon_admin = '$telepon',
            jenis_kelamin = '$jenkel',
            id_user = '$id_admin',
            created_at = '$tgl'";

        if (!mysqli_query($koneksi, $sql_admin)) {
            throw new Exception("Gagal menambahkan admin: " . mysqli_error($koneksi));
        }

        // Commit transaksi
        mysqli_commit($koneksi);
        
        $_SESSION['success_message'] = "Data berhasil ditambahkan!";

        // Gunakan JavaScript redirect sebagai alternatif jika header bermasalah
        echo "<script>
            alert('Data berhasil ditambahkan!');
            window.location.href = 'index.php?pages=pengguna_admin';
        </script>";
        exit;

        return true;

    } catch (Exception $e) {
        // Rollback jika ada error
        mysqli_rollback($koneksi);
        $_SESSION['form_errors'] = [$e->getMessage()];
        return false;
    }
}


// ================================ //
// Fungsi untuk proses Edit Admin \\
// ================================ \\

function edit_admin($DATA, $FILES)
{
    global $koneksi;
    global $tgl;

    // 1. Ambil dan bersihkan data dari form
        $id_admin = htmlspecialchars(trim($DATA['id_admin'] ?? ''));
        $nama_admin = htmlspecialchars(trim($DATA['nama_admin'] ?? ''));
        $alamat_admin = htmlspecialchars(trim($DATA['alamat_admin'] ?? ''));
        $telepon = htmlspecialchars(trim($DATA['telepon'] ?? ''));
        $jenkel = htmlspecialchars(trim($DATA['jenkel'] ?? ''));
        $email = strtolower(htmlspecialchars(trim($DATA['email'] ?? '')));


    // Validasi form kosong, email, password, dll (sama seperti fungsi tambah_admin)
    $errors = [];
    
    if (empty($nama_admin)) {
        $errors[] = "Nama Admin tidak boleh kosong";
    }
    if (empty($alamat_admin)) {
        $errors[] = "Alamat Admin tidak boleh kosong";
    }
    if (empty($telepon)) {
        $errors[] = "Telepon tidak boleh kosong";
    }
    if (empty($jenkel)) {
        $errors[] = "Jenis Kelamin tidak boleh kosong";
    }


    // Jika ada error form kosong
    if (!empty($errors)) {
        $_SESSION['form_errors'] = $errors;
        return false;
    }

    // 2. Update data di database
    $sql_user = "UPDATE tbl_users SET 
        email = '$email',
        updated_at = '$tgl'
        WHERE id_user = '$id_admin'";   

    $sql_admin = "UPDATE tbl_admin SET 
        nama_admin = '$nama_admin',
        alamat_admin = '$alamat_admin',
        telepon_admin = '$telepon',
        jenis_kelamin = '$jenkel',
        updated_at = '$tgl'
        WHERE id_user = '$id_admin'";

    if (mysqli_query($koneksi, $sql_user) && mysqli_query($koneksi, $sql_admin)) {
        $_SESSION['success_message'] = "Data berhasil diupdate!";   
        echo "<script>
            alert('Data berhasil diupdate!');
            window.location.href = 'index.php?pages=pengguna_admin';
        </script>";
        exit;   

        return true;
    } else {    
        $_SESSION['form_errors'] = ["Gagal mengupdate data: " . mysqli_error($koneksi)];
        return false;
    }   



}


// ================================ //
// Fungsi untuk proses Hapus Admin \\
// ================================ \\

function hapus_admin($id)
{
    global $koneksi;
    
    // Mulai transaction
    mysqli_begin_transaction($koneksi);
    
    try {
        // Hapus tbl_admin dulu
        $sql_admin = "DELETE FROM tbl_admin WHERE id_user = '$id'";
        if (!mysqli_query($koneksi, $sql_admin)) {
            throw new Exception("Gagal menghapus data admin: " . mysqli_error($koneksi));
        }
        
        // Hapus tbl_users
        $sql_user = "DELETE FROM tbl_users WHERE id_user = '$id'";
        if (!mysqli_query($koneksi, $sql_user)) {
            throw new Exception("Gagal menghapus data user: " . mysqli_error($koneksi));
        }
        
        // Commit jika semua berhasil
        mysqli_commit($koneksi);
        
        $_SESSION['success_message'] = "Data berhasil dihapus!";   
        echo "<script>
            alert('Data berhasil dihapus!');
            window.location.href = 'index.php?pages=pengguna_admin';
        </script>";
        exit;
        
    } catch (Exception $e) {
        // Rollback jika ada error
        mysqli_rollback($koneksi);
        $_SESSION['form_errors'] = [$e->getMessage()];
        return false;
    }
}



function hapus_admin_komentar($id)
{
    global $koneksi;
    
    /**
     * Mulai Transaction
     * Transaction adalah proses eksekusi beberapa query SQL secara bersamaan.
     * Jika salah satu query gagal, maka semua query akan dibatalkan (rollback).
     * Sebaliknya, jika semua query berhasil, maka akan disimpan permanen (commit).
     * 
     * Analogi: Seperti transfer bank. Jika pengurangan saldo berhasil tapi penambahan saldo gagal,
     * maka transaksi dibatalkan seluruhnya agar tidak merugikan salah satu pihak.
     */
    mysqli_begin_transaction($koneksi);
    
    try {
        /**
         * try - catch block
         * try: Blok kode yang akan dicoba dieksekusi
         * catch: Blok kode yang akan dijalankan jika terjadi error di blok try
         * 
         * Mirip seperti if-else, tapi khusus untuk menangani error/exception
         */
        
        // ==================== LANGKAH 1: HAPUS DATA DI TABEL ADMIN ====================
        // Hapus tbl_admin dulu karena tbl_admin adalah tabel ANAK yang merujuk ke tbl_users
        // Urutan hapus sangat penting! Anak harus dihapus dulu sebelum induk.
        $sql_admin = "DELETE FROM tbl_admin WHERE id_user = '$id'";
        
        // Eksekusi query hapus admin
        if (!mysqli_query($koneksi, $sql_admin)) {
            /**
             * throw new Exception()
             * Fungsinya untuk melempar/membuat error secara manual
             * 
             * mysqli_error($koneksi): Menangkap pesan error dari database MySQL
             * 
             * Ketika throw dijalankan, program akan langsung loncat ke blok catch
             * dan tidak melanjutkan eksekusi kode di bawahnya
             */
            throw new Exception("Gagal menghapus data admin: " . mysqli_error($koneksi));
        }
        
        // ==================== LANGKAH 2: HAPUS DATA DI TABEL USER ====================
        // Setelah admin terhapus, baru hapus user (tabel INDUK)
        $sql_user = "DELETE FROM tbl_users WHERE id_user = '$id'";
        
        // Eksekusi query hapus user
        if (!mysqli_query($koneksi, $sql_user)) {
            throw new Exception("Gagal menghapus data user: " . mysqli_error($koneksi));
        }
        
        /**
         * Commit Transaction
         * Menyimpan semua perubahan secara permanen ke database
         * 
         * Commit hanya akan dijalankan jika semua query di atas berhasil tanpa error
         * Jika sampai ke baris ini, artinya hapus admin dan user sukses semua
         */
        mysqli_commit($koneksi);
        
        // ==================== NOTIFIKASI BERHASIL ====================
        // Simpan pesan sukses ke session (bisa ditampilkan nanti)
        $_SESSION['success_message'] = "Data berhasil dihapus!";   
        
        // Tampilkan alert JavaScript dan redirect ke halaman pengguna_admin
        echo "<script>
            alert('Data berhasil dihapus!');
            window.location.href = 'index.php?pages=pengguna_admin';
        </script>";
        exit;  // Hentikan eksekusi script lebih lanjut
        
    } catch (Exception $e) {
        /**
         * catch block akan dijalankan jika ada error/exception di blok try
         * $e adalah object Exception yang berisi pesan error
         * $e->getMessage() mengambil pesan error dari throw new Exception di atas
         */
        
        /**
         * Rollback Transaction
         * Membatalkan semua perubahan yang sudah dilakukan
         * Berguna untuk menjaga konsistensi data
         * 
         * Contoh: Jika hapus admin berhasil tapi hapus user gagal,
         * maka hapus admin akan dibatalkan (rollback) sehingga data kembali utuh
         */
        mysqli_rollback($koneksi);
        
        // Simpan pesan error ke session untuk ditampilkan ke user
        $_SESSION['form_errors'] = [$e->getMessage()];
        
        // Return false menandakan proses hapus gagal
        return false;
    }
}


?>

