<?php
// 1. Amankan output agar tidak bocor teks error ke JSON
error_reporting(0);
ini_set('display_errors', 0);

session_start();
require 'config/koneksi.php';

/**
 * PENGAMANAN UTAMA:
 * Pastikan variabel keranjang selalu bertipe ARRAY.
 * Jika tidak sengaja terisi string, kita paksa reset jadi array kosong.
 */
if (!isset($_SESSION['keranjang']) || !is_array($_SESSION['keranjang'])) {
    $_SESSION['keranjang'] = [];
}

// --- LOGIKA 1: TAMBAH KE KERANJANG (AJAX/NORMAL) ---
if (isset($_GET['id'])) {
    $id_produk = mysqli_real_escape_string($conn, $_GET['id']);

    $query = mysqli_query($conn, "SELECT * FROM produk WHERE id_produk = '$id_produk'");
    $p = mysqli_fetch_assoc($query);

    if ($p) {
        if (isset($_SESSION['keranjang'][$id_produk])) {
            $_SESSION['keranjang'][$id_produk]['jumlah'] += 1;
        } else {
            $_SESSION['keranjang'][$id_produk] = [
                'nama' => $p['nama_produk'],
                'harga' => $p['harga'],
                'foto' => $p['foto'],
                'jumlah' => 1
            ];
        }
    }

    // Jika request via AJAX (dari katalog)
    if (isset($_GET['ajax'])) {
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'sukses',
            'total_item' => count($_SESSION['keranjang'])
        ]);
        exit();
    } else {
        header("Location: keranjang.php");
        exit();
    }
}

// --- LOGIKA 2: HAPUS ITEM DARI KERANJANG ---
if (isset($_GET['hapus'])) {
    $id_hapus = $_GET['hapus'];

    // Pastikan ID ada di dalam array sebelum di-unset
    if (array_key_exists($id_hapus, $_SESSION['keranjang'])) {
        unset($_SESSION['keranjang'][$id_hapus]);
    }

    header("Location: keranjang.php");
    exit();
}

// --- LOGIKA 3: UPDATE JUMLAH ITEM (DARI FORM KERANJANG) ---
if (isset($_POST['update_keranjang'])) {
    if (isset($_POST['jumlah']) && is_array($_POST['jumlah'])) {
        foreach ($_POST['jumlah'] as $id => $jml) {
            $jml = (int)$jml; // Pastikan angka

            if ($jml <= 0) {
                unset($_SESSION['keranjang'][$id]);
            } else {
                if (isset($_SESSION['keranjang'][$id])) {
                    $_SESSION['keranjang'][$id]['jumlah'] = $jml;
                }
            }
        }
    }
    header("Location: keranjang.php");
    exit();
}

// Jika file diakses tanpa parameter apa pun, lempar balik ke keranjang
header("Location: keranjang.php");
exit();
