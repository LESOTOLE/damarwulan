<?php
session_start();
require_once '../config/koneksi.php'; // 1. Koneksi harus paling atas

// 2. Cek apakah variabel $conn benar-benar ada
if (!isset($conn)) {
    die("Error: Variabel \$conn tidak ditemukan. Periksa file config/koneksi.php Tuan.");
}
require '../includes/mail_helper.php'; // Panggil helper yang kita buat tadi

if (isset($_POST['update_status'])) {
    $id_transaksi = $_POST['id_transaksi'];
    $status_baru  = $_POST['status']; // Misal: 'dikirim'

    // 1. Update status di database
    $query = mysqli_query($conn, "UPDATE transaksi SET status_transaksi = '$status_baru' WHERE id_transaksi = '$id_transaksi'");

    if ($query) {
        // 2. CEK: Jika status berubah menjadi 'dikirim', kirim email!
        if ($status_baru == 'dikirim') {

            // Ambil data email dan nama pelanggan dari database
            $sql_user = mysqli_query($conn, "SELECT u.email, u.nama FROM transaksi t 
                                             JOIN pengguna u ON t.id_pengguna = u.id_pengguna 
                                             WHERE t.id_transaksi = '$id_transaksi'");
            $data = mysqli_fetch_assoc($sql_user);

            if ($data) {
                // Panggil fungsi kirim email
                kirimNotifikasiPengiriman($data['email'], $data['nama'], $id_transaksi);
            }
        }

        header("Location: transaksi.php?pesan=berhasil");
    } else {
        header("Location: transaksi.php?pesan=gagal");
    }
}
