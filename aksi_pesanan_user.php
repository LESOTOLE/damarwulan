<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();
require 'config/koneksi.php';
require 'config/mail_sender.php';

// Proteksi: Harus login
if (!isset($_SESSION['id_pengguna'])) {
    header("Location: login.php");
    exit();
}

$id_user = $_SESSION['id_pengguna'];
$id_transaksi = '';

// Hanya proses via POST untuk mencegah CSRF
if (isset($_POST['proses_selesai']) && isset($_POST['id_transaksi'])) {
    $id_transaksi = mysqli_real_escape_string($conn, $_POST['id_transaksi']);
}

if (!empty($id_transaksi)) {
    // Verifikasi bahwa transaksi ini milik user yang sedang login DAN berstatus 'dikirim'
    $query = mysqli_query($conn, "SELECT id_transaksi FROM transaksi WHERE id_transaksi = '$id_transaksi' AND id_pengguna = '$id_user' AND status_transaksi = 'dikirim'");
    if (mysqli_num_rows($query) > 0) {
        $update = mysqli_query($conn, "UPDATE transaksi SET status_transaksi = 'selesai' WHERE id_transaksi = '$id_transaksi'");
        if ($update) {
            // Kirim email ke pelanggan bahwa pesanan telah diterima/selesai
            $qUser = mysqli_query($conn, "SELECT nama, email FROM pengguna WHERE id_pengguna = '$id_user'");
            if ($rowUser = mysqli_fetch_assoc($qUser)) {
                $email = $rowUser['email'];
                $nama = $rowUser['nama'];
                
                $mail = new PHPMailer(true);
                try {
                    $mail = setup_smtp($mail);
                    $mail->addAddress($email);
                    $mail->isHTML(true);
                    $mail->Subject = "Pesanan Selesai - Damar Wulan AutoParts";
                    $mail->Body = "Halo $nama,<br><br>Terima kasih telah mengkonfirmasi penerimaan pesanan Anda dengan nomor transaksi <b>#$id_transaksi</b>.<br>Kami harap Anda puas dengan produk kami. Ditunggu pesanan selanjutnya!<br><br>Salam,<br>Tim Damar Wulan AutoParts";
                    $mail->send();
                } catch (Exception $e) {
                    error_log("Gagal mengirim email pesanan selesai: " . $mail->ErrorInfo);
                }
            }

            $_SESSION['alert_success'] = "Pesanan #$id_transaksi berhasil dikonfirmasi selesai. Terima kasih!";
        } else {
            $_SESSION['alert_error'] = "Gagal memperbarui status pesanan.";
        }
    } else {
        $_SESSION['alert_error'] = "Akses ditolak: Pesanan tidak ditemukan, sudah selesai, atau bukan milik Anda.";
    }
}

// Redirect dinamis berdasarkan asal halaman
$redirect_url = 'riwayat.php';
if (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'detail_pesanan.php') !== false) {
    $redirect_url = $_SERVER['HTTP_REFERER'];
}

header("Location: $redirect_url");
exit();
