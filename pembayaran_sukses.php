<?php
session_start();
include 'includes/header.php';
include 'includes/navbar.php';
?>

<div class="container py-5 text-center">
    <div class="card border-0 shadow-sm p-5 mx-auto" style="max-width: 600px; border-radius: 30px;">
        <div class="mb-4">
            <i class="fas fa-check-circle text-success" style="font-size: 80px;"></i>
        </div>
        <h2 class="fw-800">Pembayaran Berhasil!</h2>
        <p class="text-muted">Terima kasih, Tuan. Pesanan Tuan sedang kami proses dan akan segera dikirim.</p>
        <hr class="my-4">
        <div class="d-flex gap-2 justify-content-center">
            <a href="index.php" class="btn btn-primary rounded-pill px-4 fw-bold">Belanja Lagi</a>
            <a href="riwayat.php" class="btn btn-outline-dark rounded-pill px-4 fw-bold">Lihat Pesanan</a>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>