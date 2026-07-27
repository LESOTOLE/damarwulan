<?php
// Konfigurasi Database
$host     = "localhost";
$username = "root";
$port     = 3306;
$password = ""; // Kosongkan jika pakai XAMPP default
$database = "damar";

// Membuat Koneksi Menggunakan MySQLi
$conn = mysqli_connect($host, $username, $password, $database, $port);

// Cek Koneksi
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Set timezone ke Waktu Indonesia Barat (WIB)
date_default_timezone_set('Asia/Jakarta');
define('XENDIT_SECRET_KEY', 'xnd_development_QvG00kY5tkOA424YsdKjXXTJixPFsLXCO6XStQ8fwstd4wdg1F9FXHneoH0jKE');
define('XENDIT_CALLBACK_TOKEN', '....................');
// Fungsi bawaan kecil (Opsional) untuk membersihkan input agar aman dari SQL Injection
function bersihkan_input($data)
{
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return mysqli_real_escape_string($conn, $data);
}
