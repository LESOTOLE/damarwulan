<?php
session_start();
require_once '../config/koneksi.php';
require_once '../includes/mail_helper.php';

if (!isset($_SESSION['id_pengguna']) || ($_SESSION['id_peran'] != 1 && $_SESSION['id_peran'] != 2)) {
    header("Location: ../login.php");
    exit();
}

if (isset($_POST['proses_kirim'])) {
    $id_transaksi = mysqli_real_escape_string($conn, $_POST['id_transaksi']);
    $nomor_resi   = mysqli_real_escape_string($conn, $_POST['nomor_resi'] ?? 'Kurir Toko');

    $sql_update = "UPDATE transaksi SET 
                   status_transaksi = 'dikirim', 
                   nomor_resi = '$nomor_resi' 
                   WHERE id_transaksi = '$id_transaksi' AND status_transaksi = 'dibayar'";

    $query_update = mysqli_query($conn, $sql_update);

    if ($query_update && mysqli_affected_rows($conn) > 0) {
        $sql_user = mysqli_query($conn, "SELECT u.nama, u.email 
                                         FROM transaksi t 
                                         JOIN pengguna u ON t.id_pengguna = u.id_pengguna 
                                         WHERE t.id_transaksi = '$id_transaksi'");
        $data = mysqli_fetch_assoc($sql_user);

        if ($data) {
            $email_tujuan = $data['email'];
            $nama_penerima = $data['nama'];
            kirimNotifikasiPengiriman($email_tujuan, $nama_penerima, $id_transaksi);
        }

        $_SESSION['alert_success'] = "Pesanan #$id_transaksi berhasil dikirim via Kurir Toko.";
        header("Location: pesanan.php");
        exit();
    } else {
        $_SESSION['alert_error'] = "Gagal memproses status pengiriman.";
        header("Location: pesanan.php");
        exit();
    }
} else {
    header("Location: pesanan.php");
    exit();
}
