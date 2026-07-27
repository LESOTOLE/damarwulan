<?php
session_start();
require 'config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Trim untuk menghapus spasi tak sengaja di awal/akhir
    $email = trim($_POST['email']);
    $pass  = $_POST['password'];

    $stmt = mysqli_prepare($conn, "SELECT * FROM pengguna WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    if ($user) {
        // CEK NAMA KOLOM: Pastikan di database namanya 'password'
        // Jika namanya berbeda, ganti $user['password'] di bawah ini
        if (password_verify($pass, $user['kata_sandi'])) {

            $_SESSION['id_pengguna'] = $user['id_pengguna'];
            $_SESSION['nama']        = $user['nama'];
            $_SESSION['id_peran']    = $user['id_peran'];

            if ($user['id_peran'] == 1 || $user['id_peran'] == 2) {
                header("Location: admin/index.php");
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            // Jika gagal, lempar balik dengan status gagal
            header("Location: login.php?status=gagal");
            exit();
        }
    } else {
        header("Location: login.php?status=gagal");
        exit();
    }
}
