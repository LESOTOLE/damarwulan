<?php
session_start();
require_once '../config/koneksi.php';

if (!isset($conn)) {
    die("Error: Variabel \$conn tidak ditemukan. Periksa file config/koneksi.php Tuan.");
}

if (isset($_POST['tambah'])) {
    $nama_area = mysqli_real_escape_string($conn, $_POST['nama_area']);
    $harga_dasar = mysqli_real_escape_string($conn, $_POST['harga_dasar']);
    $batas_berat_gram = mysqli_real_escape_string($conn, $_POST['batas_berat_gram']);
    $harga_per_kg_tambahan = mysqli_real_escape_string($conn, $_POST['harga_per_kg_tambahan']);

    $query = "INSERT INTO ongkir_area (nama_area, harga_dasar, batas_berat_gram, harga_per_kg_tambahan) 
              VALUES ('$nama_area', '$harga_dasar', '$batas_berat_gram', '$harga_per_kg_tambahan')";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Area Ongkir berhasil ditambahkan!'); window.location='ongkir.php';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan data!'); window.location='ongkir.php';</script>";
    }
}

if (isset($_POST['edit'])) {
    $id_area = mysqli_real_escape_string($conn, $_POST['id_area']);
    $nama_area = mysqli_real_escape_string($conn, $_POST['nama_area']);
    $harga_dasar = mysqli_real_escape_string($conn, $_POST['harga_dasar']);
    $batas_berat_gram = mysqli_real_escape_string($conn, $_POST['batas_berat_gram']);
    $harga_per_kg_tambahan = mysqli_real_escape_string($conn, $_POST['harga_per_kg_tambahan']);

    $query = "UPDATE ongkir_area SET 
                nama_area = '$nama_area', 
                harga_dasar = '$harga_dasar', 
                batas_berat_gram = '$batas_berat_gram', 
                harga_per_kg_tambahan = '$harga_per_kg_tambahan' 
              WHERE id_area = '$id_area'";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Data Area Ongkir berhasil diperbarui!'); window.location='ongkir.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data!'); window.location='ongkir.php';</script>";
    }
}
