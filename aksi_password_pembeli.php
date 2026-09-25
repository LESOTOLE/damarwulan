<?php
session_start();
require 'config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['id_pengguna'])) {
    $id_user = $_SESSION['id_pengguna'];
    $pass_baru = $_POST['pass_baru'];

    // Enkripsi password baru
    $hash = password_hash($pass_baru, PASSWORD_DEFAULT);

    // Update ke database
    $sql = "UPDATE pengguna SET kata_sandi = '$hash' WHERE id_pengguna = '$id_user'";
    $query = mysqli_query($conn, $sql);

    if ($query) {
        header("Location: ubah_password.php?status=sukses");
    } else {
        header("Location: ubah_password.php?status=gagal");
    }
    exit();
}
