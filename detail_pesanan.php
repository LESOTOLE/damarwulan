<?php
session_start();
require 'config/koneksi.php';

// Proteksi: Harus login
if (!isset($_SESSION['id_pengguna'])) {
    header("Location: login.php");
    exit();
}

$id_user = $_SESSION['id_pengguna'];
$id_transaksi = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : '';

// 1. Ambil data utama transaksi dan pastikan ini milik user yang sedang login
$query = mysqli_query($conn, "SELECT * FROM transaksi WHERE id_transaksi = '$id_transaksi' AND id_pengguna = '$id_user'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<script>alert('Pesanan tidak ditemukan atau Tuan tidak memiliki akses.'); window.location='riwayat.php';</script>";
    exit();
}

include 'includes/header.php';
include 'includes/navbar.php';
?>

<style>
    body {
        background-color: #f4f7fe;
    }

    .detail-card {
        border-radius: 20px;
        border: none;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    }

    .table-order th {
        color: #94a3b8;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 12px;
        letter-spacing: 1px;
        padding-bottom: 15px;
    }

    .table-order td {
        vertical-align: middle;
        padding: 15px 0;
        border-bottom: 1px dashed #e2e8f0;
    }
</style>

<div class="container py-5 mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-800 text-navy mb-1">Detail <span class="text-primary">Pesanan</span></h2>
            <p class="text-muted small mb-0">Rincian lengkap dari invoice #<?= $data['id_transaksi'] ?></p>
        </div>
        <a href="riwayat.php" class="btn btn-light border px-4 py-2 fw-bold" style="border-radius: 12px;">
            <i class="fas fa-arrow-left me-2"></i> KEMBALI
        </a>
    </div>

    <div class="row g-4">
        <!-- Kolom Kiri: Daftar Barang -->
        <div class="col-lg-8">
            <div class="card detail-card p-4 p-md-5 mb-4">
                <h5 class="fw-bold mb-4 border-bottom pb-3"><i class="fas fa-box-open text-primary me-2"></i> Barang yang Dibeli</h5>

                <div class="table-responsive">
                    <table class="table table-borderless table-order mb-0">
                        <thead>
                            <tr class="border-bottom">
                                <th style="min-width: 200px;">Produk</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Harga</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $q_items = mysqli_query($conn, "
                                SELECT dt.*, p.nama_produk, p.gambar 
                                FROM detail_transaksi dt 
                                JOIN produk p ON dt.id_produk = p.id_produk 
                                WHERE dt.id_transaksi = '$id_transaksi'
                            ");
                            $total_belanja = 0;
                            while ($item = mysqli_fetch_assoc($q_items)):
                                $sub = $item['jumlah'] * $item['harga_satuan'];
                                $total_belanja += $sub;
                                $img_src = (!empty($item['gambar'])) ? "assets/img/" . $item['gambar'] : "assets/img/default-part.jpg";
                            ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="<?= $img_src ?>" onerror="this.src='assets/img/default-part.jpg';" width="50" height="50" class="rounded-3 object-fit-cover me-3 border">
                                            <span class="fw-bold text-dark"><?= $item['nama_produk']; ?></span>
                                        </div>
                                    </td>
                                    <td class="text-center fw-bold text-muted"><?= $item['jumlah']; ?>x</td>
                                    <td class="text-end text-muted">Rp <?= number_format($item['harga_satuan'], 0, ',', '.'); ?></td>
                                    <td class="text-end fw-bold text-navy">Rp <?= number_format($sub, 0, ',', '.'); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end text-muted pt-4">Subtotal Produk</td>
                                <td class="text-end fw-bold pt-4">Rp <?= number_format($total_belanja, 0, ',', '.'); ?></td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end text-muted pb-4">Biaya Pengiriman</td>
                                <td class="text-end fw-bold pb-4">Rp <?= number_format($data['ongkir'] ?? 0, 0, ',', '.'); ?></td>
                            </tr>
                            <tr class="border-top">
                                <td colspan="3" class="text-end fw-800 text-navy pt-4 fs-5">TOTAL BAYAR</td>
                                <td class="text-end fw-900 text-danger pt-4 fs-4">Rp <?= number_format($data['total_bayar'], 0, ',', '.'); ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Status & Pengiriman -->
        <div class="col-lg-4">
            <!-- Status Card -->
            <div class="card detail-card p-4 mb-4">
                <h6 class="fw-bold mb-3 text-uppercase" style="font-size: 13px; letter-spacing: 1px;">Status Pesanan</h6>

                <?php
                $s = $data['status_transaksi'];
                $badge = 'bg-secondary';
                if ($s == 'pending') $badge = 'bg-warning';
                if ($s == 'dibayar') $badge = 'bg-success';
                if ($s == 'dikirim') $badge = 'bg-primary';
                if ($s == 'selesai') $badge = 'bg-dark';
                ?>
                <span class="badge <?= $badge ?> rounded-pill px-4 py-2 text-uppercase d-inline-block w-100 fs-6 mb-3">
                    <?= $s; ?>
                </span>

                <p class="text-muted small mb-1">Tanggal Transaksi</p>
                <p class="fw-bold text-dark mb-0"><i class="far fa-clock me-2"></i> <?= date('d M Y, H:i', strtotime($data['tgl_transaksi'])); ?> WIB</p>
                <?php if ($s == 'dikirim'): ?>
                    <hr>
                    <button type="button" class="btn btn-success w-100 py-3 fw-bold rounded-4 shadow-sm mt-2 btn-terima-pesanan" data-id="<?= $data['id_transaksi']; ?>">
                        KONFIRMASI DITERIMA <i class="fas fa-check-circle ms-2"></i>
                    </button>
                    <form id="form-terima-<?= $data['id_transaksi']; ?>" action="aksi_pesanan_user.php" method="POST" style="display: none;">
                        <input type="hidden" name="id_transaksi" value="<?= $data['id_transaksi']; ?>">
                        <input type="hidden" name="proses_selesai" value="1">
                    </form>
                <?php endif; ?>
            </div>

            <!-- Pengiriman Card -->
            <div class="card detail-card p-4">
                <h6 class="fw-bold mb-3 text-uppercase border-bottom pb-2" style="font-size: 13px; letter-spacing: 1px;">Informasi Pengiriman</h6>

                <p class="text-muted small mb-1">Alamat Tujuan</p>
                <p class="fw-bold text-dark mb-4" style="line-height: 1.6;">
                    <i class="fas fa-map-marker-alt text-danger me-2"></i> <?= nl2br($data['alamat_pengiriman']); ?>
                </p>

                <?php if (!empty($data['catatan'])): ?>
                    <p class="text-muted small mb-1">Catatan Pesanan</p>
                    <div class="p-3 bg-light rounded-3 small fst-italic text-muted mb-4">
                        "<?= $data['catatan']; ?>"
                    </div>
                <?php endif; ?>

                <?php if ($s == 'pending' && !empty($data['xendit_invoice_url'])): ?>
                    <a href="<?= $data['xendit_invoice_url'] ?>" target="_blank" class="btn btn-primary w-100 py-3 fw-bold rounded-4 shadow-sm mt-2">
                        LANJUTKAN PEMBAYARAN <i class="fas fa-external-link-alt ms-2"></i>
                    </a>
                <?php endif; ?>
            </div>
        </div>
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