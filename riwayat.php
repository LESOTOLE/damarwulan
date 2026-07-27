<?php
session_start();
require 'config/koneksi.php';

// Proteksi: Harus login
if (!isset($_SESSION['id_pengguna'])) {
    header("Location: login.php");
    exit();
}

include 'includes/header.php';
include 'includes/navbar.php';

$id_user = $_SESSION['id_pengguna'];
?>

<style>
    body {
        background-color: #f4f7fe;
    }

    .order-card {
        border-radius: 20px;
        border: none;
        transition: all 0.3s ease;
    }

    .order-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.08) !important;
    }

    /* Timeline Status */
    .status-tracker {
        display: flex;
        justify-content: space-between;
        position: relative;
        margin-bottom: 20px;
    }

    .status-step {
        text-align: center;
        flex: 1;
        z-index: 2;
    }

    .step-icon {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        background: #e2e8f0;
        color: #94a3b8;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 8px;
        font-size: 14px;
        transition: 0.3s;
    }

    .step-text {
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        color: #94a3b8;
    }

    /* Active States */
    .step-active .step-icon {
        background: var(--primary-navy);
        color: white;
        box-shadow: 0 0 15px rgba(26, 35, 126, 0.3);
    }

    .step-active .step-text {
        color: var(--primary-navy);
    }

    .status-line {
        position: absolute;
        top: 17px;
        left: 10%;
        width: 80%;
        height: 2px;
        background: #e2e8f0;
        z-index: 1;
    }
</style>

<div class="container py-5 mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4 animate__animated animate__fadeIn">
        <div>
            <h2 class="fw-800 text-navy mb-0">Pesanan <span class="text-primary">Saya</span></h2>
            <p class="text-muted small mb-0">Lacak status suku cadang yang Tuan beli di sini.</p>
        </div>
        <a href="index.php" class="btn btn-navy px-4 py-2 fw-bold shadow-sm" style="border-radius: 12px;">
            <i class="fas fa-shopping-bag me-2"></i> KATALOG
        </a>
    </div>

    <div class="row g-4">
        <?php
        $sql = "SELECT * FROM transaksi WHERE id_pengguna = '$id_user' ORDER BY tgl_transaksi DESC";
        $query = mysqli_query($conn, $sql);

        if (mysqli_num_rows($query) > 0):
            while ($row = mysqli_fetch_assoc($query)):
                $status = $row['status_transaksi'];
        ?>
                <div class="col-md-12 animate__animated animate__fadeInUp">
                    <div class="card order-card shadow-sm p-4 mb-3">
                        <div class="row align-items-center">
                            <div class="col-lg-3 border-end mb-3 mb-lg-0">
                                <small class="text-muted d-block mb-1">NOMOR INVOICE</small>
                                <h6 class="fw-800 text-navy mb-3">#<?= $row['id_transaksi']; ?></h6>
                                <small class="text-muted d-block mb-1">TOTAL BAYAR</small>
                                <h5 class="fw-800 text-danger">Rp <?= number_format($row['total_bayar'], 0, ',', '.'); ?></h5>
                                <p class="text-muted" style="font-size: 11px;"><?= date('d M Y, H:i', strtotime($row['tgl_transaksi'])); ?> WIB</p>
                            </div>

                            <div class="col-lg-6 mb-3 mb-lg-0">
                                <div class="status-tracker">
                                    <div class="status-line"></div>

                                    <div class="status-step <?= ($status == 'pending' || $status == 'dibayar' || $status == 'dikirim' || $status == 'selesai') ? 'step-active' : ''; ?>">
                                        <div class="step-icon"><i class="fas fa-file-invoice"></i></div>
                                        <div class="step-text">Dibuat</div>
                                    </div>

                                    <div class="status-step <?= ($status == 'dibayar' || $status == 'dikirim' || $status == 'selesai') ? 'step-active' : ''; ?>">
                                        <div class="step-icon"><i class="fas fa-wallet"></i></div>
                                        <div class="step-text">Dibayar</div>
                                    </div>

                                    <div class="status-step <?= ($status == 'dikirim' || $status == 'selesai') ? 'step-active' : ''; ?>">
                                        <div class="step-icon"><i class="fas fa-truck"></i></div>
                                        <div class="step-text">Dikirim</div>
                                    </div>

                                    <div class="status-step <?= ($status == 'selesai') ? 'step-active' : ''; ?>">
                                        <div class="step-icon"><i class="fas fa-check-double"></i></div>
                                        <div class="step-text">Selesai</div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 text-lg-end">
                                <?php if ($status == 'pending'): ?>
                                    <a href="<?= $row['xendit_invoice_url']; ?>" target="_blank" class="btn btn-primary w-100 py-3 fw-bold rounded-4 shadow-sm">
                                        BAYAR SEKARANG <i class="fas fa-external-link-alt ms-2"></i>
                                    </a>
                                <?php elseif ($status == 'dikirim'): ?>
                                    <a href="detail_pesanan.php?id=<?= $row['id_transaksi']; ?>" class="btn btn-light border w-100 py-2 fw-bold rounded-4 mb-2">
                                        LIHAT DETAIL <i class="fas fa-chevron-right ms-2"></i>
                                    </a>
                                    <button type="button" class="btn btn-success w-100 py-2 fw-bold rounded-4 shadow-sm btn-terima-pesanan" data-id="<?= $row['id_transaksi']; ?>">
                                        TERIMA PESANAN <i class="fas fa-check-circle ms-2"></i>
                                    </button>
                                    <form id="form-terima-<?= $row['id_transaksi']; ?>" action="aksi_pesanan_user.php" method="POST" style="display: none;">
                                        <input type="hidden" name="id_transaksi" value="<?= $row['id_transaksi']; ?>">
                                        <input type="hidden" name="proses_selesai" value="1">
                                    </form>
                                <?php else: ?>
                                    <a href="detail_pesanan.php?id=<?= $row['id_transaksi']; ?>" class="btn btn-light border w-100 py-3 fw-bold rounded-4">
                                        LIHAT DETAIL <i class="fas fa-chevron-right ms-2"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            endwhile;
        else:
            ?>
            <div class="col-12 text-center py-5">
                <img src="https://cdn-icons-png.flaticon.com/512/6134/6134065.png" width="120" class="mb-4 opacity-25">
                <h5 class="text-muted">Tuan belum pernah melakukan pemesanan.</h5>
                <a href="index.php" class="btn btn-navy mt-3 px-4 py-2">Mulai Belanja</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const buttons = document.querySelectorAll('.btn-terima-pesanan');
    buttons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const idTransaksi = this.getAttribute('data-id');
            Swal.fire({
                title: 'Konfirmasi Penerimaan',
                text: `Apakah Tuan yakin pesanan #${idTransaksi} sudah sampai dan diterima dengan baik?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Sudah Diterima!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`form-terima-${idTransaksi}`).submit();
                }
            });
        });
    });
});
</script>

<?php include 'includes/footer.php'; ?>