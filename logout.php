<?php
session_start();

// Hapus semua data session
session_unset();

// Hancurkan session
session_destroy();

// Mulai session baru hanya untuk mengirim pesan sukses ke SweetAlert
session_start();
$_SESSION['alert_success'] = "Anda telah berhasil keluar.";

// Alihkan ke halaman login
header("Location: login.php");
exit();
