<?php
session_start();
require_once '../config/koneksi.php';

if (!isset($conn)) {
    die("Error: Variabel \$conn tidak ditemukan. Periksa file config/koneksi.php Tuan.");
}

// Pastikan yang akses sudah login
if (!isset($_SESSION['id_pengguna'])) {
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_user = $_SESSION['id_pengguna'];
    $pass_baru = $_POST['pass_baru'];

    // Langsung enkripsi password baru
    $hash_baru = password_hash($pass_baru, PASSWORD_DEFAULT);

    // Update database
    $update = mysqli_query($conn, "UPDATE pengguna SET password = '$hash_baru' WHERE id_pengguna = '$id_user'");

    if ($update) {
        // Berhasil, arahkan balik dengan status sukses
        header("Location: ubah_password.php?status=sukses");
    } else {
        // Gagal (biasanya karena masalah koneksi/query)
        header("Location: ubah_password.php?status=error");
    }
    exit();
} else {
    header("Location: ubah_password.php");
    exit();
}
