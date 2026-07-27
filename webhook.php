<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// 1. Pengaturan Awal & Koneksi
require 'config/koneksi.php';

// Ambil data JSON yang dikirimkan oleh Xendit
$rawRequest = file_get_contents("php://input");
$data = json_decode($rawRequest, true);

// -- LOGGING WEBHOOK --
// Kita simpan log ini ke file webhook.log untuk mengecek apa yang dikirim Xendit
file_put_contents('webhook.log', "[" . date('Y-m-d H:i:s') . "] PAYLOAD: " . $rawRequest . PHP_EOL, FILE_APPEND);
// ---------------------

// 2. Keamanan: Ambil Callback Token dari Xendit Dashboard Tuan
// Masukkan token yang ada di Settings > Callbacks di Xendit ke sini
$xenditCallbackToken = 'lh7l5pY80mPu92FvF7c4i4xXcy1IzHplxLCyvG6ctLhqiilp';

// Ambil token yang dikirim Xendit di header
$receivedToken = '';
if (isset($_SERVER['HTTP_X_CALLBACK_TOKEN'])) {
    $receivedToken = $_SERVER['HTTP_X_CALLBACK_TOKEN'];
} else {
    $headers = function_exists('getallheaders') ? getallheaders() : [];
    foreach ($headers as $key => $value) {
        if (strtolower($key) === 'x-callback-token') {
            $receivedToken = $value;
            break;
        }
    }
}

// Verifikasi apakah kiriman ini benar-benar dari Xendit Tuan
if ($receivedToken !== $xenditCallbackToken) {
    file_put_contents('webhook.log', "[" . date('Y-m-d H:i:s') . "] TOKEN SALAH! Received: $receivedToken\n", FILE_APPEND);
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Token tidak valid']);
    exit;
}
file_put_contents('webhook.log', "[" . date('Y-m-d H:i:s') . "] TOKEN BENAR\n", FILE_APPEND);

// 3. Logika Update Status
if (isset($data['status']) && $data['status'] === 'PAID') {
    file_put_contents('webhook.log', "[" . date('Y-m-d H:i:s') . "] LOGIKA PAID DIMULAI\n", FILE_APPEND);

    $id_transaksi = $data['external_id']; // Ini adalah ID Transaksi sistem kita
    $metode_bayar = $data['payment_method'];
    $waktu_bayar  = date('Y-m-d H:i:s');

    // Update status transaksi menjadi 'dibayar' di database
    $query = "UPDATE transaksi SET 
              status_transaksi = 'dibayar', 
              metode_bayar = '$metode_bayar'
              WHERE id_transaksi = '$id_transaksi'";

    file_put_contents('webhook.log', "[" . date('Y-m-d H:i:s') . "] QUERY: $query\n", FILE_APPEND);

    $update = mysqli_query($conn, $query);

    if ($update) {
        $affected = mysqli_affected_rows($conn);
        file_put_contents('webhook.log', "[" . date('Y-m-d H:i:s') . "] BERHASIL UPDATE, AFFECTED ROWS: $affected\n", FILE_APPEND);
        // AMBIL DATA PELANGGAN & DETAIL TRANSAKSI
        // Kita butuh email dan nomor HP pelanggan untuk tahu ke mana invoice/notif harus dikirim
        $sql_user = mysqli_query($conn, "SELECT u.email, u.nama, u.no_telepon, t.total_bayar FROM transaksi t 
                                     JOIN pengguna u ON t.id_pengguna = u.id_pengguna 
                                     WHERE t.id_transaksi = '$id_transaksi'");
        $data_user = mysqli_fetch_assoc($sql_user);
        $email_tujuan = $data_user['email'] ?? '';
        $nama_pelanggan = $data_user['nama'] ?? '';
        $no_hp = $data_user['no_telepon'] ?? '';
        $total = $data_user['total_bayar'] ?? 0;

        // PANGGIL FUNGSI KIRIM EMAIL
        file_put_contents('webhook.log', "[" . date('Y-m-d H:i:s') . "] MEMULAI KIRIM EMAIL...\n", FILE_APPEND);
        kirimEmailInvoice($email_tujuan, $nama_pelanggan, $id_transaksi, $total);
        file_put_contents('webhook.log', "[" . date('Y-m-d H:i:s') . "] EMAIL SELESAI\n", FILE_APPEND);

        // PANGGIL FUNGSI KIRIM WA
        $pesan_wa = "Halo *$nama_pelanggan*,\n\nTerima kasih telah berbelanja di *Damar Wulan Ac & Radiator*.\nKami telah menerima pembayaran Kamu untuk pesanan *#$id_transaksi* sebesar *Rp " . number_format($total, 0, ',', '.') . "*.\n\nPesanan Kamu sedang kami proses. Kamu bisa memantau statusnya di menu Pesanan Saya.\n\nSalam hangat,\n*Damar Wulan AC & Radiator*";
        if (!empty($no_hp)) {
            file_put_contents('webhook.log', "[" . date('Y-m-d H:i:s') . "] MEMULAI KIRIM WA...\n", FILE_APPEND);
            kirimWA($no_hp, $pesan_wa);
            file_put_contents('webhook.log', "[" . date('Y-m-d H:i:s') . "] WA SELESAI\n", FILE_APPEND);
        }

        http_response_code(200);
        file_put_contents('webhook.log', "[" . date('Y-m-d H:i:s') . "] WEBHOOK SUCCESS 200\n", FILE_APPEND);
        echo json_encode(['status' => 'success']);
    } else {
        file_put_contents('webhook.log', "[" . date('Y-m-d H:i:s') . "] GAGAL UPDATE: " . mysqli_error($conn) . "\n", FILE_APPEND);
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Gagal update database']);
    }
} else {
    file_put_contents('webhook.log', "[" . date('Y-m-d H:i:s') . "] STATUS BUKAN PAID\n", FILE_APPEND);
    // Jika statusnya bukan PAID (misal: EXPIRED)
    http_response_code(200);
    echo json_encode(['status' => 'ignored', 'message' => 'Bukan status pembayaran']);
}
// --- FUNGSI KIRIM EMAIL DENGAN PHPMAILER ---

function kirimEmailInvoice($ke, $nama, $no_inv, $total)
{
    require 'vendor/autoload.php'; // Sesuaikan dengan path PHPMailer Tuan

    $mail = new PHPMailer(true);

    try {
        // Konfigurasi SMTP (Contoh menggunakan Gmail)
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'wavesupra23@gmail.com'; // Email pengirim
        $mail->Password   = 'fyhh deta wcyr bbal';      // Password Aplikasi Gmail
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Penerima
        $mail->setFrom('no-reply@damarwulan.com', 'Damar Wulan AutoParts');
        $mail->addAddress($ke, $nama);

        // Isi Email (Format HTML agar Mewah)
        $mail->isHTML(true);
        $mail->Subject = 'Pembayaran Diterima - Invoice #' . $no_inv;
        $mail->Body    = "
            <div style='font-family: sans-serif; padding: 20px; border: 1px solid #eee;'>
                <h2 style='color: #1a237e;'>Pembayaran Berhasil!</h2>
                <p>Halo <b>$nama</b>, terima kasih telah berbelanja.</p>
                <p>Kami telah menerima pembayaran Kamu untuk pesanan <b>#$no_inv</b> sebesar <b>Rp " . number_format($total, 0, ',', '.') . "</b>.</p>
                <hr>
                <p>Suku cadang Kamu sedang kami siapkan untuk segera dikirim. kamu bisa memantau statusnya di menu <b>Pesanan Saya</b>.</p>
                <br>
                <p>Salam hangat,<br><b>Damar Wulan Ac & Radiator</b></p>
            </div>";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

// --- FUNGSI KIRIM WHATSAPP DENGAN FONNTE ---

function kirimWA($target, $pesan)
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.fonnte.com/send',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => array(
            'target' => $target,
            'message' => $pesan,
            'countryCode' => '62', // Optional: paksa gunakan kode negara 62
        ),
        CURLOPT_HTTPHEADER => array(
            'Authorization: AKRN7EoFe4hVAxuapKu8' // GANTI DENGAN TOKEN FONNTE TUAN
        ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}
