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


/**
 * =================================================================
 * FUNGSI UPLOAD FILE
 * =================================================================
 * Fungsi ini digunakan untuk mengupload file gambar dengan berbagai
 * konfigurasi yang bisa disesuaikan untuk setiap fitur.
 * Sesuaikan parameter input sesuai fitur yang ada di aplikasi.
 * 
 * BISA DIGUNAKAN UNTUK:
 * - Upload foto admin
 * - Upload foto kasir
 * - Upload foto dokter
 * - Upload foto pasien
 * - Upload foto produk obat
 * - Dan lain-lain
 * 
 * @param array $file_input    File dari form input (contoh: $_FILES['photo'])
 * @param string $identifier   Kode unik untuk identifikasi (contoh: id_admin, kode_obat, no_rm)
 * @param string $target_dir   Folder tujuan penyimpanan (contoh: '../images/admin/')
 * @param array $options       Opsi tambahan (ukuran, ekstensi, prefix, dll)
 * 
 * @return array               Hasil proses upload (success, message, filename, dll)
 * =================================================================
 */
function upload_file($file_input, $identifier, $target_dir, $options = []) 
{
    // =============================================================
    // BAGIAN 1: SETTING DEFAULT OPSI
    // =============================================================
    // Kita buatkan nilai default, ini antisipasi jika user saat upload data tidak memberikan opsi tambahan
    $defaults = [
        'max_size' => 1048576,                    // 1MB = 1024 x 1024 byte
        'allowed_extensions' => ['jpg', 'jpeg', 'png', 'bmp'],  // Ekstensi yang diizinkan
        'create_folder' => true,                  // Buat folder otomatis jika belum ada
        'prefix' => ''                            // Awalan nama file (contoh: 'ADM', 'DR', 'PROD')
    ];
    
    // Menggabungkan opsi user dengan default (opsi user akan menimpa default)
    $options = array_merge($defaults, $options);
    
    // =============================================================
    // BAGIAN 2: VALIDASI INPUT FILE
    // =============================================================
    // Cek apakah struktur file yang diupload valid
    if (!isset($file_input['error']) || !isset($file_input['tmp_name'])) {
        return [
            'success' => false, 
            'message' => 'File input tidak valid. Pastikan form menggunakan enctype="multipart/form-data"'
        ];
    }
    
    // Ambil informasi file yang diupload
    $error      = $file_input['error'];      // Kode error (0 = sukses, 4 = tidak ada file)
    $tmpName    = $file_input['tmp_name'];   // Lokasi temporary file di server
    $namaAsli   = $file_input['name'];       // Nama asli file dari user (contoh: "foto selfie.jpg")
    $ukuranFile = $file_input['size'];       // Ukuran file dalam byte
    
    // =============================================================
    // BAGIAN 3: CEK APAKAH USER UPLOAD FILE
    // =============================================================
    // UPLOAD_ERR_NO_FILE = 4 artinya user tidak memilih file sama sekali
    if ($error === UPLOAD_ERR_NO_FILE) {
        return [
            'success' => false, 
            'message' => 'Tidak ada file yang diupload. Silakan pilih gambar terlebih dahulu.'
        ];
    }
    
    // =============================================================
    // BAGIAN 4: CEK ERROR UPLOAD LAINNYA
    // =============================================================
    // UPLOAD_ERR_OK = 0 artinya upload berhasil
    if ($error !== UPLOAD_ERR_OK) {
        // Array untuk mapping kode error ke pesan yang mudah dipahami
        $error_messages = [
            UPLOAD_ERR_INI_SIZE   => 'Ukuran file melebihi batas maksimum server (upload_max_filesize)',
            UPLOAD_ERR_FORM_SIZE  => 'Ukuran file melebihi batas maksimum form (MAX_FILE_SIZE)',
            UPLOAD_ERR_PARTIAL    => 'File hanya terupload sebagian',
            UPLOAD_ERR_NO_TMP_DIR => 'Folder temporary tidak ditemukan',
            UPLOAD_ERR_CANT_WRITE => 'Gagal menulis file ke disk',
            UPLOAD_ERR_EXTENSION  => 'Upload file dihentikan oleh ekstensi PHP'
        ];
        
        $message = isset($error_messages[$error]) 
                    ? $error_messages[$error] 
                    : 'Terjadi error upload yang tidak diketahui (Kode: ' . $error . ')';
        
        return ['success' => false, 'message' => $message];
    }
    
    // =============================================================
    // BAGIAN 5: AMBIL EKSTENSI FILE
    // =============================================================
    // pathinfo() mengambil informasi path file
    // PATHINFO_EXTENSION mengambil ekstensi file (contoh: dari "foto.jpg" menjadi "jpg")
    $ekstensiFile = strtolower(pathinfo($namaAsli, PATHINFO_EXTENSION));
    
    // Debug: Untuk mempelajari, bisa lihat ekstensi yang terdeteksi
    // echo "Ekstensi file: " . $ekstensiFile . "<br>";
    
    // =============================================================
    // BAGIAN 6: VALIDASI EKSTENSI FILE
    // =============================================================
    // Cek apakah ekstensi file termasuk dalam daftar yang diizinkan
    if (!in_array($ekstensiFile, $options['allowed_extensions'])) {
        // Buat daftar ekstensi yang diizinkan menjadi string (contoh: "jpg, jpeg, png")
        $ext_str = implode(', ', $options['allowed_extensions']);
        
        return [
            'success' => false, 
            'message' => "File yang anda upload BUKAN gambar. Yang diizinkan: $ext_str. 
                        File anda berekstensi: .$ekstensiFile"
        ];
    }
    
    // =============================================================
    // BAGIAN 7: VALIDASI UKURAN FILE
    // =============================================================
    // Cek apakah ukuran file melebihi batas maksimal
    if ($ukuranFile > $options['max_size']) {
        // Konversi byte ke MB agar lebih mudah dipahami user
        $max_mb = round($options['max_size'] / 1048576, 2);
        $file_mb = round($ukuranFile / 1048576, 2);
        
        return [
            'success' => false, 
            'message' => "Ukuran file terlalu besar. Maksimal {$max_mb}MB, 
                        file anda {$file_mb}MB"
        ];
    }
    
    // =============================================================
    // BAGIAN 8: MEMBUAT FOLDER JIKA BELUM ADA
    // =============================================================
    // Cek apakah folder tujuan sudah ada
    if ($options['create_folder'] && !is_dir($target_dir)) {
        // Buat folder dengan permission 0777 (bisa dibaca/tulis semua user)
        // true artinya buat folder secara rekursif (termasuk parent folder jika belum ada)
        if (!mkdir($target_dir, 0777, true)) {
            return [
                'success' => false, 
                'message' => "Gagal membuat direktori: $target_dir. 
                            Cek permission folder server Anda."
            ];
        }
    }
    
    // =============================================================
    // BAGIAN 9: MEMBUAT NAMA FILE BARU YANG UNIK
    // =============================================================
    // Mengapa perlu nama baru? 
    // 1. Menghindari nama file duplikat
    // 2. Menghindari karakter aneh (spasi, emoji, dll)
    // 3. Keamanan lebih baik
    // 4. Mudah diidentifikasi
    
    // Format nama file: PREFIX_IDENTIFIER_TIMESTAMP_UNIQID.EKSTENSI
    // Contoh: ADM_admin123_20250115_67c8f4a2b3c1d.jpg
    
    $prefix = $options['prefix'] ? $options['prefix'] . '_' : '';  // Tambah underscore jika ada prefix
    
    // uniqid() menghasilkan string unik berdasarkan microtime
    // Contoh output: "67c8f4a2b3c1d"
    $unique_id = uniqid();
    
    // Bisa juga tambahkan timestamp untuk lebih informatif
    $timestamp = date('Ymd_His');
    
    // Gabungkan semua menjadi nama file baru
    $namaFileBaru = $prefix . $identifier . "_" . $timestamp . "_" . $unique_id . "." . $ekstensiFile;
    // Contoh hasil: ADM_admin123_20250115_143025_67c8f4a2b3c1d.jpg
    
    // =============================================================
    // BAGIAN 10: TENTUKAN PATH LENGKAP FILE
    // =============================================================
    // rtrim() menghapus slash di akhir target_dir (jika ada)
    // lalu tambahkan slash dan nama file baru
    $file_path = rtrim($target_dir, '/') . '/' . $namaFileBaru;
    
    // =============================================================
    // BAGIAN 11: PINDAHKAN FILE DARI TEMPORARY KE FOLDER TUJUAN
    // =============================================================
    // move_uploaded_file() adalah fungsi KHUSUS untuk file upload
    // Lebih aman daripada copy() atau rename() karena memvalidasi file asli dari upload
    if (move_uploaded_file($tmpName, $file_path)) {
        // =========================================================
        // BAGIAN 12: UPLOAD SUKSES - KEMBALIKAN INFORMASI LENGKAP
        // =========================================================
        return [
            'success' => true,                          // Status sukses
            'message' => 'File berhasil diupload',      // Pesan sukses
            'filename_asli' => $namaAsli,               // Nama asli dari user
            'filename_tersimpan' => $namaFileBaru,      // Nama baru di server
            'path' => $file_path,                       // Path lengkap file
            'size' => $ukuranFile,                      // Ukuran file dalam byte
            'size_mb' => round($ukuranFile / 1048576, 2), // Ukuran dalam MB
            'extension' => $ekstensiFile,               // Ekstensi file
            'identifier' => $identifier,                // Identitas yang digunakan
            'prefix' => $options['prefix']              // Prefix yang digunakan
        ];
    } else {
        // =========================================================
        // BAGIAN 13: UPLOAD GAGAL
        // =========================================================
        return [
            'success' => false, 
            'message' => 'Gagal mengupload file. Cek permission folder ' . $target_dir . 
                        ' Pastikan folder bisa ditulis oleh web server.'
        ];
    }
}


/**
 * =================================================================
 * FUNGSI TAMBAH ADMIN
 * =================================================================
 * Fungsi untuk menambahkan data admin baru beserta upload foto
 * 
 * @param array $DATA   Data dari $_POST (field text form)
 * @param array $FILES  Data dari $_FILES (file upload)
 * @return bool         True jika berhasil, False jika gagal
 * =================================================================
 */
function tambah_admin($DATA, $FILES, $target_folder = '../images/users/image_all/')
{
    global $koneksi;
    global $waktu_sekarang;

    // =============================================================
    // BAGIAN 1: Ambil dan bersihkan data dari form
    // =============================================================
    $id_admin     = htmlspecialchars(trim($DATA['id_admin'] ?? ''));
    $nama_admin   = htmlspecialchars(trim($DATA['nama_admin'] ?? ''));
    $alamat_admin = htmlspecialchars(trim($DATA['alamat_admin'] ?? ''));
    $telepon      = htmlspecialchars(trim($DATA['telepon'] ?? ''));
    $jenkel       = htmlspecialchars(trim($DATA['jenkel'] ?? ''));
    $email        = strtolower(htmlspecialchars(trim($DATA['email'] ?? '')));
    $password     = $DATA['password'] ?? '';
    $password2    = $DATA['password2'] ?? '';
    $role         = htmlspecialchars(trim($DATA['role'] ?? ''));

    // =============================================================
    // BAGIAN 2: Validasi form kosong
    // =============================================================
    $errors = [];
    
    if (empty($id_admin)) $errors[] = "ID Admin tidak boleh kosong";
    if (empty($nama_admin)) $errors[] = "Nama Admin tidak boleh kosong";
    if (empty($alamat_admin)) $errors[] = "Alamat Admin tidak boleh kosong";
    if (empty($telepon)) $errors[] = "Telepon tidak boleh kosong";
    if (empty($jenkel)) $errors[] = "Jenis Kelamin tidak boleh kosong";
    if (empty($email)) $errors[] = "Email tidak boleh kosong";
    if (empty($password)) $errors[] = "Password tidak boleh kosong";
    if (empty($password2)) $errors[] = "Konfirmasi Password tidak boleh kosong";
    if (empty($role)) $errors[] = "Role tidak boleh kosong";


    // Jika ada error form kosong
    if (!empty($errors)) {
        $_SESSION['form_errors'] = $errors;
        return false;
    }

    // =============================================================
    // BAGIAN 3: Validasi email sudah terdaftar
    // =============================================================
    $result = mysqli_query($koneksi, "SELECT email FROM tbl_users WHERE email = '$email'");
    if (mysqli_fetch_assoc($result)) {
        $_SESSION['form_errors'] = ['Email yang diinput sudah terdaftar di database!'];
        return false;
    }

    // =============================================================
    // BAGIAN 4: Validasi konfirmasi password
    // =============================================================
    if ($password !== $password2) {
        $_SESSION['form_errors'] = ['Konfirmasi Password tidak sesuai!'];
        return false;
    }

    // =============================================================
    // BAGIAN 5: Validasi minimal panjang password
    // =============================================================
    if (strlen($password) < 6) {
        $_SESSION['form_errors'] = ['Password minimal 6 karakter!'];
        return false;
    }

    // =============================================================
    // PROSES UPLOAD FOTO (OPSIONAL) - PAKAI $target_folder
    // =============================================================
    $gambar_foto = null;
    
    if (isset($FILES['photo']) && $FILES['photo']['error'] !== UPLOAD_ERR_NO_FILE) {
        
        if ($FILES['photo']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['form_errors'] = ['Terjadi error upload file'];
            return false;
        }
        
        // PAKAI $target_folder dari parameter!
        $upload_result = upload_file(
            $FILES['photo'],
            $id_admin,
            $target_folder,  // ← FLEKSIBEL, bisa diisi folder apapun
            [
                'max_size' => 1048576,
                'allowed_extensions' => ['jpg', 'jpeg', 'png', 'bmp'],
                'create_folder' => true,
                'prefix' => 'ADM'
            ]
        );
        
        if ($upload_result['success']) {
            $gambar_foto = $upload_result['filename_tersimpan'];
        } else {
            $_SESSION['form_errors'] = [$upload_result['message']];
            return false;
        }
    }

    // =============================================================
    // BAGIAN 7: Enkripsi password
    // =============================================================
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // =============================================================
    // BAGIAN 8: Transaksi database
    // =============================================================
    mysqli_begin_transaction($koneksi);

    try {
        // 🔴 PERBAIKAN UTAMA: Tambahkan kolom foto/path_photo_admin
        // Sesuaikan nama kolom dengan database Anda:
        // - Jika nama kolom adalah 'foto' → pakai 'foto'
        // - Jika nama kolom adalah 'path_photo_admin' → pakai 'path_photo_admin'
        
        $sql_user = "INSERT INTO tbl_users SET 
            id_user = '$id_admin',
            email = '$email',
            password = '$password_hash',
            role = (SELECT id_tipe_user FROM tbl_tipe_user WHERE tipe_user = 'Admin'),
            created_at = '$waktu_sekarang'";

        if (!mysqli_query($koneksi, $sql_user)) {
            throw new Exception("Gagal menambahkan user: " . mysqli_error($koneksi));
        }

        // Insert ke tbl_admin
        $sql_admin = "INSERT INTO tbl_admin SET 
            nama_admin = '$nama_admin',
            alamat_admin = '$alamat_admin',
            telepon_admin = '$telepon',
            jenis_kelamin = '$jenkel',
            path_photo_admin = " . ($gambar_foto ? "'$gambar_foto'" : "NULL") . ",
            id_user = '$id_admin',
            created_at = '$waktu_sekarang'";

        if (!mysqli_query($koneksi, $sql_admin)) {
            throw new Exception("Gagal menambahkan admin: " . mysqli_error($koneksi));
        }

        // Commit transaksi
        mysqli_commit($koneksi);
        
        $_SESSION['success_message'] = "Data berhasil ditambahkan!";
        
        // 🔴 PERBAIKAN: Hapus echo script di sini, redirect di file tambah.php
        return true;

    } catch (Exception $e) {
        // Rollback jika ada error
        mysqli_rollback($koneksi);
        
        // Hapus file foto yang sudah terupload jika database gagal
        if (!empty($gambar_foto)) {
            $file_path = '../images/users/admin/' . $gambar_foto;
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }
        
        $_SESSION['form_errors'] = [$e->getMessage()];
        return false;
    }
}

/**
 * =================================================================
 * FUNGSI EDIT ADMIN
 * =================================================================
 * Fungsi untuk mengupdate data admin 
 * - Upload foto OPSIONAL (tidak wajib)
 * - Jika upload foto baru, foto lama akan dihapus dari folder
 * - Jika tidak upload foto, nama foto tetap sama didatabase
 * 
 * @param array     $DATA   Data dari $_POST
 * @param array     $FILES  Data dari $_FILES
 * @param string    $target_folder Folder tujuan upload foto
 * @return bool     True jika berhasil, False jika gagal
 * =================================================================
 */
function edit_admin($DATA, $FILES, $target_folder = '../images/users/admin/')
{
    global $koneksi;
    global $waktu_sekarang;

    // =============================================================
    // BAGIAN 1: Ambil dan bersihkan data dari form
    // =============================================================
    $id_admin     = htmlspecialchars(trim($DATA['id_admin'] ?? ''));
    $nama_admin   = htmlspecialchars(trim($DATA['nama_admin'] ?? ''));
    $alamat_admin = htmlspecialchars(trim($DATA['alamat_admin'] ?? ''));
    $telepon      = htmlspecialchars(trim($DATA['telepon'] ?? ''));
    $jenkel       = htmlspecialchars(trim($DATA['jenkel'] ?? ''));
    $email        = strtolower(htmlspecialchars(trim($DATA['email'] ?? '')));

    // =============================================================
    // BAGIAN 2: Validasi form kosong
    // =============================================================
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

    if (!empty($errors)) {
        $_SESSION['form_errors'] = $errors;
        return false;
    }

    // =============================================================
    // BAGIAN 3: Ambil data foto LAMA dari database
    // =============================================================
    $query_foto = "SELECT path_photo_admin FROM tbl_admin WHERE id_user = '$id_admin'";
    $result_foto = mysqli_query($koneksi, $query_foto);
    $row_foto = mysqli_fetch_assoc($result_foto);
    $foto_lama = $row_foto['path_photo_admin'] ?? '';
    
    // =============================================================
    // BAGIAN 4: PROSES UPLOAD FOTO BARU (OPSIONAL)
    // =============================================================
    $foto_baru = $foto_lama; // Default: tetap pakai foto lama
    
    // Cek apakah user mengupload file baru
    if (isset($FILES['photo']) && $FILES['photo']['error'] !== UPLOAD_ERR_NO_FILE) {
        
        // Cek error upload
        if ($FILES['photo']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['form_errors'] = ['Terjadi error saat upload file'];
            return false;
        }
        
        // Proses upload file photo baru
        $upload_result = upload_file(
            $FILES['photo'],
            $id_admin,
            $target_folder,
            [
                'max_size' => 1048576,
                'allowed_extensions' => ['jpg', 'jpeg', 'png', 'bmp'],
                'create_folder' => true,
                'prefix' => 'ADM'
            ]
        );
        
        if ($upload_result['success']) {
            // Upload berhasil, ambil nama file baru
            $foto_baru = $upload_result['filename_tersimpan'];
            
            // HAPUS FILE FOTO LAMA yang ada di folder jika ada dan berbeda dengan foto baru
            if (!empty($foto_lama) && $foto_lama != $foto_baru) {
                $file_path_lama = rtrim($target_folder, '/') . '/' . $foto_lama;
                if (file_exists($file_path_lama)) {
                    unlink($file_path_lama); // Hapus file foto lama
                }
            }
        } else {
            $_SESSION['form_errors'] = [$upload_result['message']];
            return false;
        }
    }
    // Jika tidak upload file, variable $foto_baru kita isi dengan variable $foto_lama
    
    // =============================================================
    // BAGIAN 5: Update database
    // =============================================================
    
    // Update tbl_users, email dan password tidak diupdate disini, hanya updated_at saja
    $sql_user = "UPDATE tbl_users SET 
        updated_at = '$waktu_sekarang'
        WHERE id_user = '$id_admin'";
    
    // Update tbl_admin (dengan atau tanpa foto, karena sudah di atur diatas)
    $sql_admin = "UPDATE tbl_admin SET 
        nama_admin = '$nama_admin',
        alamat_admin = '$alamat_admin',
        telepon_admin = '$telepon',
        jenis_kelamin = '$jenkel',
        path_photo_admin = '$foto_baru',
        updated_at = '$waktu_sekarang'
        WHERE id_user = '$id_admin'";
    
    // Eksekusi query
    $result_user = mysqli_query($koneksi, $sql_user);
    $result_admin = mysqli_query($koneksi, $sql_admin);
    
    if ($result_user && $result_admin) {
        $_SESSION['success_message'] = "Data berhasil diupdate!";
        return true;
    } else {
        // Jika update gagal dan ada foto baru yang sudah terupload, hapus foto tersebut
        if ($foto_baru != $foto_lama && !empty($foto_baru)) {
            $file_path_baru = rtrim($target_folder, '/') . '/' . $foto_baru;
            if (file_exists($file_path_baru)) {
                unlink($file_path_baru);
            }
        }
        
        $_SESSION['form_errors'] = ["Gagal mengupdate data: " . mysqli_error($koneksi)];
        return false;
    }
}


/**
 * =================================================================
 * FUNGSI HAPUS ADMIN (DENGAN TRANSACTION)
 * =================================================================
 * Untuk tabel berelasi, transaction WAJIB digunakan
 * 
 * @param string    $id  ID User/Admin yang akan dihapus
 * @param string    $target_folder Folder tempat penyimpanan foto
 * @return bool     True jika berhasil, False jika gagal
 * =================================================================
 */
function hapus_admin($id, $target_folder = '../images/users/admin/')
{
    global $koneksi;
    
    // =============================================================
    // BAGIAN 1: Ambil nama file foto SEBELUM transaksi dimulai
    // =============================================================
    $query_foto = "SELECT path_photo_admin FROM tbl_admin WHERE id_user = '$id'";
    $result_foto = mysqli_query($koneksi, $query_foto);
    $row_foto = mysqli_fetch_assoc($result_foto);
    $foto = $row_foto['path_photo_admin'] ?? '';
    
    // =============================================================
    // BAGIAN 2: Mulai transaksi
    // =============================================================
    mysqli_begin_transaction($koneksi);
    
    try {
        // Langkah 1: Hapus dari tbl_admin (child table)
        $sql_admin = "DELETE FROM tbl_admin WHERE id_user = '$id'";
        if (!mysqli_query($koneksi, $sql_admin)) {
            throw new Exception("Gagal menghapus data admin: " . mysqli_error($koneksi));
        }
        
        // Langkah 2: Hapus dari tbl_users (parent table)
        $sql_user = "DELETE FROM tbl_users WHERE id_user = '$id'";
        if (!mysqli_query($koneksi, $sql_user)) {
            throw new Exception("Gagal menghapus data user: " . mysqli_error($koneksi));
        }
        
        // =========================================================
        // Jika semua query berhasil, COMMIT (simpan perubahan)
        // =========================================================
        mysqli_commit($koneksi);
        
        // =========================================================
        // Setelah database berhasil dihapus, hapus file foto
        // =========================================================
        if (!empty($foto)) {
            $file_path = rtrim($target_folder, '/') . '/' . $foto;
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }
        
        $_SESSION['success_message'] = "Data admin dan foto berhasil dihapus!";
        return true;
        
    } catch (Exception $e) {
        // =========================================================
        // Jika ada error, ROLLBACK (batalkan semua perubahan)
        // =========================================================
        mysqli_rollback($koneksi);
        $_SESSION['form_errors'] = [$e->getMessage()];
        return false;
    }
}


function hapus_admin_sederhana($id, $target_folder = '../images/users/admin/')
{
    global $koneksi;
    
    // Ambil nama file foto
    $query_foto = "SELECT path_photo_admin FROM tbl_admin WHERE id_user = '$id'";
    $result_foto = mysqli_query($koneksi, $query_foto);
    $row_foto = mysqli_fetch_assoc($result_foto);
    $foto = $row_foto['path_photo_admin'] ?? '';
    
    // Hapus tbl_admin (child) dulu
    $sql_admin = "DELETE FROM tbl_admin WHERE id_user = '$id'";
    $hapus_admin = mysqli_query($koneksi, $sql_admin);
    
    // Hapus tbl_users (parent)
    $sql_user = "DELETE FROM tbl_users WHERE id_user = '$id'";
    $hapus_user = mysqli_query($koneksi, $sql_user);
    
    if ($hapus_admin && $hapus_user) {
        if (!empty($foto)) {
            $file_path = rtrim($target_folder, '/') . '/' . $foto;
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }
        return true;
    }
    
    return false;
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

// ===============================================================
// FUNGSI GANTI PASSWORD ADMIN
// ===============================================================
// Digunakan untuk mengganti password user admin yang sedang di-edit
// Data $POST yang dikirim dari modal di edit.php
// ===============================================================

function ganti_password($DATA)
{
    global $koneksi;
    global $waktu_sekarang;
    
    // ==========================================
    // 1. AMBIL DATA DARI FORM
    // ==========================================
    $id_user = mysqli_real_escape_string($koneksi, $DATA['id_user_password'] ?? '');
    $pass_lama = $DATA['pass_lama'] ?? '';
    $pass_baru = $DATA['pass_baru'] ?? '';
    $pass_baru_confirm = $DATA['pass_baru_confirm'] ?? '';
    
    // ==========================================
    // 2. VALIDASI KOSONG
    // ==========================================
    $errors = [];
    
    if (empty($id_user)) $errors[] = "ID User tidak ditemukan!";
    if (empty($pass_lama)) $errors[] = "Password lama tidak boleh kosong!";
    if (empty($pass_baru)) $errors[] = "Password baru tidak boleh kosong!";
    if (empty($pass_baru_confirm)) $errors[] = "Konfirmasi password tidak boleh kosong!";
    
    if (!empty($errors)) {
        $_SESSION['form_errors'] = $errors;
        return false;
    }
    
    // ==========================================
    // 3. CEK PASSWORD LAMA DI DATABASE
    // ==========================================
    $sql_cek = "SELECT password FROM tbl_users WHERE id_user = '$id_user'";
    $result_cek = mysqli_query($koneksi, $sql_cek);
    
    if (mysqli_num_rows($result_cek) == 0) {
        $_SESSION['form_errors'] = ["User tidak ditemukan di database!"];
        return false;
    }
    
    $user_data = mysqli_fetch_assoc($result_cek);
    
    // Verifikasi password lama (karena pakai password_hash di registrasi)
    if (!password_verify($pass_lama, $user_data['password'])) {
        $_SESSION['form_errors'] = ["Password lama yang Anda masukkan SALAH!"];
        return false;
    }
    
    // ==========================================
    // 4. VALIDASI PASSWORD BARU
    // ==========================================
    if (strlen($pass_baru) < 6) {
        $_SESSION['form_errors'] = ["Password baru minimal 6 karakter!"];
        return false;
    }
    
    if ($pass_baru !== $pass_baru_confirm) {
        $_SESSION['form_errors'] = ["Konfirmasi password baru tidak sesuai!"];
        return false;
    }
    
    // Cek apakah password baru sama dengan password lama
    if (password_verify($pass_baru, $user_data['password'])) {
        $_SESSION['form_errors'] = ["Password baru tidak boleh sama dengan password lama!"];
        return false;
    }
    
    // ==========================================
    // 5. UPDATE PASSWORD KE DATABASE
    // ==========================================
    $password_hash = password_hash($pass_baru, PASSWORD_DEFAULT);
    
    $sql_update = "UPDATE tbl_users SET 
                    password = '$password_hash',
                    updated_at = '$waktu_sekarang'
                WHERE id_user = '$id_user'";
    
    if (mysqli_query($koneksi, $sql_update)) {
        if (mysqli_affected_rows($koneksi) > 0) {
            $_SESSION['success_message'] = "Password berhasil diubah!";
            return true;
        } else {
            $_SESSION['form_errors'] = ["Tidak ada perubahan pada password."];
            return false;
        }
    } else {
        $_SESSION['form_errors'] = ["Gagal mengupdate password: " . mysqli_error($koneksi)];
        return false;
    }
}





// ======================== \\

?>

