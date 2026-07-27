<?php
session_start();
// 1. KONEKSI & PROTEKSI
require 'config/koneksi.php';

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

if (!isset($_SESSION['id_pengguna']) || empty($_SESSION['keranjang'])) {
    header("Location: index.php");
    exit();
}

// 2. PENGATURAN XENDIT
$secret_key = defined('XENDIT_SECRET_KEY') ? XENDIT_SECRET_KEY : '';

// 3. DATA DASAR & PENGAMBILAN ALAMAT OTOMATIS
$id_user      = $_SESSION['id_pengguna'];
$id_transaksi = uniqid("DW-");
$tgl          = date('Y-m-d H:i:s');
$total        = 0;

// AMBIL ALAMAT DARI TABEL PENGGUNA
$query_user = mysqli_query($conn, "
    SELECT p.alamat, p.id_area, o.harga_dasar, o.batas_berat_gram, o.harga_per_kg_tambahan 
    FROM pengguna p 
    LEFT JOIN ongkir_area o ON p.id_area = o.id_area 
    WHERE p.id_pengguna = '$id_user'
");
$data_user  = mysqli_fetch_assoc($query_user);
$alamat_kirim = $data_user['alamat'];

// Proteksi jika alamat atau zona masih kosong di profil
if (empty($alamat_kirim) || empty($data_user['id_area'])) {
    echo "<script>alert('Alamat atau Zona pengiriman belum diatur. Silakan lengkapi alamat Tuan terlebih dahulu.'); window.location='alamat.php';</script>";
    exit();
}

// Catatan tetap diambil dari form jika ada (misal: 'titip di satpam')
$catatan = isset($_POST['catatan']) ? mysqli_real_escape_string($conn, $_POST['catatan']) : '';

$total_berat_gram = 0;

// Batch query untuk berat produk (Optimasi N+1)
$product_ids = array_keys($_SESSION['keranjang']);
$berat_produk_map = [];
if (!empty($product_ids)) {
    $in_ids = implode(',', array_map('intval', $product_ids));
    $q_berat = mysqli_query($conn, "SELECT id_produk, berat_gram FROM produk WHERE id_produk IN ($in_ids)");
    while ($row_berat = mysqli_fetch_assoc($q_berat)) {
        $berat_produk_map[$row_berat['id_produk']] = $row_berat['berat_gram'];
    }
}

foreach ($_SESSION['keranjang'] as $id_produk => $item) {
    $total += $item['harga'] * $item['jumlah'];
    $berat = isset($berat_produk_map[$id_produk]) ? $berat_produk_map[$id_produk] : 0;
    $total_berat_gram += ($berat * $item['jumlah']);
}

// HITUNG ONGKIR
$ongkir = 0;
if (!empty($data_user['id_area'])) {
    $ongkir = $data_user['harga_dasar'];
    if ($total_berat_gram > $data_user['batas_berat_gram']) {
        $extra_gram = $total_berat_gram - $data_user['batas_berat_gram'];
        $extra_kg = ceil($extra_gram / 1000);
        $ongkir += ($extra_kg * $data_user['harga_per_kg_tambahan']);
    }
}

$total_tagihan = $total + $ongkir;

// 4. SIMPAN KE TABEL TRANSAKSI (MENGGUNAKAN TRANSACTION)
mysqli_begin_transaction($conn);

try {
    $query_trans = mysqli_query($conn, "INSERT INTO transaksi (id_transaksi, id_pengguna, tgl_transaksi, total_bayar, ongkir, status_transaksi, metode_bayar, alamat_pengiriman, catatan) 
                                        VALUES ('$id_transaksi', '$id_user', '$tgl', '$total_tagihan', '$ongkir', 'pending', 'Xendit', '$alamat_kirim', '$catatan')");

    if (!$query_trans) {
        throw new Exception("Gagal menyimpan transaksi: " . mysqli_error($conn));
    }

    // 5. SIMPAN DETAIL & POTONG STOK
    foreach ($_SESSION['keranjang'] as $id_produk => $item) {
        $harga_satuan = $item['harga'];
        $qty = $item['jumlah'];

        $insert_detail = mysqli_query($conn, "INSERT INTO detail_transaksi (id_transaksi, id_produk, jumlah, harga_satuan) 
                             VALUES ('$id_transaksi', '$id_produk', '$qty', '$harga_satuan')");
        if (!$insert_detail) throw new Exception("Gagal simpan detail transaksi.");

        $update_stok = mysqli_query($conn, "UPDATE produk SET stok = stok - $qty WHERE id_produk = '$id_produk'");
        if (!$update_stok) throw new Exception("Gagal update stok barang.");
    }

    // 6. INTEGRASI API XENDIT
    $data_invoice = [
        'external_id' => $id_transaksi,
        'amount'      => $total_tagihan,
        'payer_email' => $_SESSION['email'] ?? 'customer@mail.com',
        'description' => 'Pembayaran Suku Cadang Damar Wulan AutoParts',
        'invoice_duration' => 86400,
        'success_redirect_url' => 'http://localhost/damar/pembayaran_sukses.php',
    ];

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => 'https://api.xendit.co/v2/invoices',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_USERPWD => $secret_key . ':',
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($data_invoice),
        CURLOPT_HTTPHEADER => ['Content-Type: application/json']
    ]);

    $response = curl_exec($curl);
    $result = json_decode($response, true);
    curl_close($curl);

    if (isset($result['invoice_url'])) {
        $url_invoice = $result['invoice_url'];
        mysqli_query($conn, "UPDATE transaksi SET xendit_invoice_url = '$url_invoice' WHERE id_transaksi = '$id_transaksi'");

        mysqli_commit($conn); // Simpan permanen

        unset($_SESSION['keranjang']);
        header("Location: " . $url_invoice);
        exit();
    } else {
        throw new Exception("Gagal memanggil API Xendit. Respons: " . $response);
    }

} catch (Exception $e) {
    mysqli_rollback($conn); // Batalkan semua query jika terjadi error
    echo "Terjadi kesalahan sistem, transaksi dibatalkan. " . $e->getMessage();
}
