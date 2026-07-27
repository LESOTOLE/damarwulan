<?php
// 1. Panggil autoloader Composer
require 'vendor/autoload.php';

// 2. Import class PHPMailer ke namespace saat ini
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    // --- MODE DEBUG (Sangat Penting untuk Mencari Error) ---
    // Ubah ke SMTP::DEBUG_SERVER untuk melihat detail komunikasi dengan Google
    $mail->SMTPDebug = SMTP::DEBUG_OFF;

    // --- KONFIGURASI SMTP ---
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'lokontolhehe@gmail.com';                // Email Gmail Tuan
    $mail->Password   = 'fudm rljy flyc hhrq';                // 16 Digit App Password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // --- PENGIRIM & PENERIMA ---
    $mail->setFrom('no-reply@damarwulan.com', 'Damar Wulan AutoParts');
    $mail->addAddress('okeegayn@gmail.com');             // Email tujuan untuk tes

    // --- KONTEN ---
    $mail->isHTML(true);
    $mail->Subject = 'Tes Koneksi SMTP Composer';
    $mail->Body    = 'Halo Tuan, jika Tuan membaca ini berarti <b>PHPMailer via Composer</b> sudah sukses!';

    $mail->send();
    echo 'Pesan Berhasil Terkirim!';
} catch (Exception $e) {
    echo "Pesan Gagal Terkirim. Error: {$mail->ErrorInfo}";
}
