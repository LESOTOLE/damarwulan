<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'config/koneksi.php';
require 'config/mail_sender.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama  = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass  = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role  = 3; // Default Pelanggan

    $insert = mysqli_query($conn, "INSERT INTO pengguna (nama, email, kata_sandi, id_peran) VALUES ('$nama', '$email', '$pass', '$role')");

    if ($insert) {
        // Kirim email notifikasi registrasi
        $mail = new PHPMailer(true);
        try {
            $mail = setup_smtp($mail);
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = "Pendaftaran Berhasil - Damar Wulan AutoParts";
            $mail->Body = "Halo $nama,<br><br>Terima kasih telah mendaftar di Damar Wulan AutoParts. Akun Anda telah berhasil dibuat.<br><br>Salam,<br>Tim Damar Wulan AutoParts";
            $mail->send();
        } catch (Exception $e) {
            error_log("Gagal mengirim email registrasi: " . $mail->ErrorInfo);
        }

        header("Location: login.php?status=registrasi_sukses");
    } else {
        header("Location: register.php?status=error");
    }
    exit();
}
