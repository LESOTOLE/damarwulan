<?php
session_start();
require_once '../config/koneksi.php';

if (!isset($conn)) {
    die("Error: Variabel \$conn tidak ditemukan. Periksa file config/koneksi.php Tuan.");
}

// PROSES TAMBAH
if (isset($_POST['simpan_produk'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama_produk']);
    $id_kategori = (int)$_POST['id_kategori'];
    $merk = mysqli_real_escape_string($conn, $_POST['merk']);
    $stok = (int)$_POST['stok'];
    $harga = (int)$_POST['harga'];

    // Handle Upload Gambar
    $gambar = "default-part.jpg";
    if (isset($_FILES['gambar']['name']) && $_FILES['gambar']['name'] != "") {
        $nama_file = time() . "_" . basename($_FILES['gambar']['name']);
        $tmp = $_FILES['gambar']['tmp_name'];
        if(move_uploaded_file($tmp, "../assets/img/" . $nama_file)){
            $gambar = $nama_file;
        }
    }

    $query = "INSERT INTO produk (nama_produk, id_kategori, merk, harga, stok, gambar) 
              VALUES ('$nama', $id_kategori, '$merk', $harga, $stok, '$gambar')";

    if (mysqli_query($conn, $query)) {
        $_SESSION['alert_success'] = "Produk berhasil ditambahkan!";
    } else {
        $_SESSION['alert_error'] = "Gagal menambah produk: " . mysqli_error($conn);
    }
    header("Location: produk.php");
    exit();
}

// PROSES EDIT
if (isset($_POST['edit_produk'])) {
    $id_produk = (int)$_POST['id_produk'];
    $nama = mysqli_real_escape_string($conn, $_POST['nama_produk']);
    $id_kategori = (int)$_POST['id_kategori'];
    $merk = mysqli_real_escape_string($conn, $_POST['merk']);
    $stok = (int)$_POST['stok'];
    $harga = (int)$_POST['harga'];

    // Cek apakah upload gambar baru
    $update_gambar = "";
    if (isset($_FILES['gambar']['name']) && $_FILES['gambar']['name'] != "") {
        $nama_file = time() . "_" . basename($_FILES['gambar']['name']);
        $tmp = $_FILES['gambar']['tmp_name'];
        if(move_uploaded_file($tmp, "../assets/img/" . $nama_file)){
            // Hapus gambar lama jika ada
            $cek = mysqli_query($conn, "SELECT gambar FROM produk WHERE id_produk='$id_produk'");
            if($cek && mysqli_num_rows($cek) > 0){
                $data = mysqli_fetch_assoc($cek);
                if (!empty($data['gambar']) && $data['gambar'] != 'default-part.jpg' && file_exists("../assets/img/" . $data['gambar'])) {
                    unlink("../assets/img/" . $data['gambar']);
                }
            }
            $update_gambar = ", gambar = '$nama_file'";
        }
    }

    $query = "UPDATE produk SET 
              nama_produk = '$nama', 
              id_kategori = $id_kategori, 
              merk = '$merk', 
              harga = $harga, 
              stok = $stok 
              $update_gambar
              WHERE id_produk = $id_produk";

    if (mysqli_query($conn, $query)) {
        $_SESSION['alert_success'] = "Produk berhasil diupdate!";
    } else {
        $_SESSION['alert_error'] = "Gagal mengupdate produk: " . mysqli_error($conn);
    }
    header("Location: produk.php");
    exit();
}

// PROSES HAPUS
if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    
    // Hapus gambar jika ada kolom gambar
    $cek_kolom = mysqli_query($conn, "SHOW COLUMNS FROM produk LIKE 'gambar'");
    if(mysqli_num_rows($cek_kolom) > 0) {
        $cek = mysqli_query($conn, "SELECT gambar FROM produk WHERE id_produk='$id'");
        if ($cek && mysqli_num_rows($cek) > 0) {
            $data = mysqli_fetch_assoc($cek);
            if (!empty($data['gambar']) && $data['gambar'] != 'default-part.jpg' && file_exists("../assets/img/" . $data['gambar'])) {
                unlink("../assets/img/" . $data['gambar']);
            }
        }
    }

    $query = "DELETE FROM produk WHERE id_produk='$id'";
    if (mysqli_query($conn, $query)) {
        $_SESSION['alert_success'] = "Produk berhasil dihapus!";
    } else {
        $_SESSION['alert_error'] = "Gagal menghapus produk: " . mysqli_error($conn);
    }
    header("Location: produk.php");
    exit();
}

header("Location: produk.php");
exit();
