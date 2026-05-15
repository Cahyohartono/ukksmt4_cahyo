<?php
session_start();
require_once '../../inc/functions.php'; // Sesuaikan dengan path yang benar

// Set header untuk JSON response
header('Content-Type: application/json');

// Untuk debugging - lihat error PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Debug: log yang diterima
error_log('=== PROSES MODAL ADMIN ===');
error_log('POST data: ' . print_r($_POST, true));
error_log('FILES data: ' . print_r($_FILES, true));

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // PROSES TAMBAH ADMIN
    if (isset($_POST['tambah_modal'])) {
        error_log('Proses TAMBAH Admin');
        
        // Cek apakah fungsi tambah_admin ada
        if (!function_exists('tambah_admin')) {
            error_log('ERROR: Fungsi tambah_admin tidak ditemukan!');
            echo json_encode([
                'status' => 'error',
                'message' => 'Fungsi tambah_admin tidak ditemukan di functions.php'
            ]);
            exit;
        }
        
        $result = tambah_admin($_POST, $_FILES, '../../images/users/admin/');
        error_log('Hasil tambah_admin: ' . ($result === true ? 'TRUE' : 'FALSE'));
        
        if ($result === true) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Data berhasil ditambahkan!'
            ]);
        } else {
            $errorMessage = isset($_SESSION['form_errors']) ? implode(', ', $_SESSION['form_errors']) : 'Gagal menambahkan data';
            unset($_SESSION['form_errors']);
            echo json_encode([
                'status' => 'error',
                'message' => $errorMessage
            ]);
        }
        exit;
    }
    
    // PROSES EDIT ADMIN
    if (isset($_POST['edit_modal'])) {
        error_log('Proses EDIT Admin');
        
        // Cek apakah fungsi edit_admin ada
        if (!function_exists('edit_admin')) {
            error_log('ERROR: Fungsi edit_admin tidak ditemukan!');
            echo json_encode([
                'status' => 'error',
                'message' => 'Fungsi edit_admin tidak ditemukan di functions.php'
            ]);
            exit;
        }
        
        $result = edit_admin($_POST, $_FILES, '../../images/users/admin/');
        error_log('Hasil edit_admin: ' . ($result === true ? 'TRUE' : 'FALSE'));
        
        if ($result === true) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Data berhasil diupdate!'
            ]);
        } else {
            $errorMessage = isset($_SESSION['form_errors']) ? implode(', ', $_SESSION['form_errors']) : 'Gagal mengupdate data';
            unset($_SESSION['form_errors']);
            echo json_encode([
                'status' => 'error',
                'message' => $errorMessage
            ]);
        }
        exit;
    }
    
    // PROSES GANTI PASSWORD
    if (isset($_POST['ganti_password'])) {
        error_log('Proses GANTI PASSWORD');
        
        if (!function_exists('ganti_password')) {
            error_log('ERROR: Fungsi ganti_password tidak ditemukan!');
            echo json_encode([
                'status' => 'error',
                'message' => 'Fungsi ganti_password tidak ditemukan di functions.php'
            ]);
            exit;
        }
        
        $result = ganti_password($_POST);
        
        if ($result === true) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Password berhasil diubah!'
            ]);
        } else {
            $errorMessage = isset($_SESSION['form_errors']) ? implode(', ', $_SESSION['form_errors']) : 'Gagal mengubah password';
            unset($_SESSION['form_errors']);
            echo json_encode([
                'status' => 'error',
                'message' => $errorMessage
            ]);
        }
        exit;
    }
}

// Jika tidak ada action yang sesuai
echo json_encode([
    'status' => 'error',
    'message' => 'Invalid request. No matching action found.'
]);
?>