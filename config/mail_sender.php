<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load library
require_once __DIR__ . '/../vendor/autoload.php';

function setup_smtp($mail)
{
    // --- KONFIGURASI INTI SMTP ---
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';                     // Server SMTP Gmail
    $mail->SMTPAuth   = true;                                 // Aktifkan Autentikasi
    $mail->Username   = 'wavesupra23@gmail.com';                // Email Gmail Tuan
    $mail->Password   = 'fyhh deta wcyr bbal';                // 16 Digit App Password (Bukan password login!)
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;      // Enkripsi TLS
    $mail->Port       = 587;                                  // Port untuk TLS

    // Pengaturan Default Pengirim
    $mail->setFrom('no-reply@damarwulan.com', 'Damar Wulan AC Radiator');

    return $mail;
}
