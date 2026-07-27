<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';

function kirimNotifikasiPengiriman($email_tujuan, $nama_pelanggan, $id_transaksi)
{
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'wavesupra23@gmail.com';
        $mail->Password   = 'fyhh deta wcyr bbal'; // App Password 16 digit 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('no-reply@damarwulan.com', 'Damar Wulan AutoParts');
        $mail->addAddress($email_tujuan, $nama_pelanggan);

        $mail->isHTML(true);
        $mail->Subject = 'Update Pengiriman: Pesanan #' . $id_transaksi;

        $mail->Body = "
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: auto; border: 1px solid #dddddd; border-radius: 10px; overflow: hidden;'>
                <div style='background-color: #1a237e; color: #ffffff; padding: 20px; text-align: center;'>
                    <h2 style='margin: 0;'>Pesanan Kamu Dalam Perjalanan!</h2>
                </div>
                <div style='padding: 20px; color: #333333; line-height: 1.6;'>
                    <p>Halo <b>$nama_pelanggan</b>,</p>
                    <p>Kami ingin mengabarkan bahwa pesanan Kamu dengan ID Transaksi: <b>#$id_transaksi</b> telah resmi kami serahkan ke kurir untuk dikirim.</p>
                    
                    <div style='background-color: #f8f9fa; border-left: 4px solid #1a237e; padding: 15px; margin: 20px 0;'>
                        <p style='margin: 0;'><b>Status:</b> Sedang Dikirim</p>
                        <p style='margin: 0;'><b>Referensi:</b> Gunakan ID Transaksi di atas jika ingin bertanya pada Admin.</p>
                    </div>

                    <p>Kamu dapat melihat detail pesanan dan memantau statusnya melalui tombol di bawah ini:</p>
                    
                    <div style='text-align: center; margin: 30px 0;'>
                        <a href='http://damarwulan.com/riwayat.php' 
                           style='background-color: #d32f2f; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold;'>
                           CEK PESANAN SAYA
                        </a>
                    </div>

                    <p>Terima kasih telah berbelanja di Damar Wulan Ac & Radiator!</p>
                </div>
                <div style='background-color: #f1f1f1; padding: 15px; text-align: center; font-size: 12px; color: #777777;'>
                    &copy; " . date('Y') . " Damar Wulan AutoParts. All rights reserved.
                </div>
            </div>";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
