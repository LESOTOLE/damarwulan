<?php
require 'config/koneksi.php';

// Masukkan email akun Owner Tuan di sini
$email_target = "namaemail@gmail.com";
$password_baru = "admin123";
$hash_baru = password_hash($password_baru, PASSWORD_DEFAULT);

// Update langsung lewat sistem agar tidak ada kesalahan karakter
$update = mysqli_query($conn, "UPDATE pengguna SET kata_sandi = '$hash_baru' WHERE email = '$email_target'");

if ($update) {
    echo "<h1>Berhasil!</h1>";
    echo "Password untuk <b>$email_target</b> sekarang adalah: <b>$password_baru</b><br>";
    echo "Silakan coba login kembali.";
} else {
    echo "Gagal update: " . mysqli_error($conn);
}
