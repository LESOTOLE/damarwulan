<?php
session_start();
require_once '../config/koneksi.php'; // 1. Koneksi harus paling atas

// 2. Cek apakah variabel $conn benar-benar ada
if (!isset($conn)) {
    die("Error: Variabel \$conn tidak ditemukan. Periksa file config/koneksi.php Tuan.");
}


// Pastikan hanya owner yang bisa akses
if (!isset($_SESSION['id_pengguna']) || $_SESSION['id_peran'] != 1) {
    exit();
}

// 1. TAMBAH
if (isset($_POST['tambah'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $id_peran = $_POST['id_peran'];
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);

    mysqli_query($conn, "INSERT INTO pengguna (nama, email, password, id_peran) VALUES ('$nama', '$email', '$pass', '$id_peran')");
    header("Location: pengguna.php?status=sukses");
}

// 2. EDIT
if (isset($_POST['edit'])) {
    $id = $_POST['id_pengguna'];
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $id_peran = $_POST['id_peran'];

    if (!empty($_POST['password'])) {
        $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $sql = "UPDATE pengguna SET nama='$nama', email='$email', password='$pass', id_peran='$id_peran' WHERE id_pengguna='$id'";
    } else {
        $sql = "UPDATE pengguna SET nama='$nama', email='$email', id_peran='$id_peran' WHERE id_pengguna='$id'";
    }

    mysqli_query($conn, $sql);
    header("Location: pengguna.php?status=sukses");
}

// 3. HAPUS
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    if ($id != $_SESSION['id_pengguna']) {
        mysqli_query($conn, "DELETE FROM pengguna WHERE id_pengguna='$id'");
    }
    header("Location: pengguna.php?status=sukses");
}
