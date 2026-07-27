<?php
session_start();
require 'config/koneksi.php';
include 'includes/header.php';
include 'includes/navbar.php';

$keranjang = $_SESSION['keranjang'] ?? [];
?>

<style>
    .cart-card {
        border-radius: 20px;
        overflow: hidden;
        border: none;
    }

    .item-row {
        border-bottom: 1px solid #eee;
        transition: 0.3s;
    }

    .item-row:hover {
        background: #f8fafc;
    }

    .qty-input {
        width: 60px;
        text-align: center;
        border-radius: 8px;
        border: 1px solid #ddd;
    }

    .summary-card {
        background: #fff;
        border-radius: 20px;
        position: sticky;
        top: 100px;
    }
</style>

<div class="container py-5 mt-5">
    <h2 class="fw-800 text-navy mb-4 animate__animated animate__fadeIn">Keranjang <span class="text-primary">Belanja</span></h2>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card cart-card shadow-sm animate__animated animate__fadeInLeft">
                <div class="card-body p-0">
                    <form action="aksi_keranjang.php" method="POST">
                        <table class="table align-middle mb-0">
                            <thead class="bg-light text-muted small">
                                <tr>
                                    <th class="ps-4 py-3">PRODUK</th>
                                    <th>HARGA</th>
                                    <th class="text-center">QTY</th>
                                    <th class="text-end pe-4">SUBTOTAL</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $total_akhir = 0;
                                if (!empty($keranjang)):
                                    foreach ($keranjang as $id => $item):
                                        $subtotal = $item['harga'] * $item['jumlah'];
                                        $total_akhir += $subtotal;
                                ?>
                                        <tr class="item-row">
                                            <td class="ps-4 py-3">
                                                <div class="d-flex align-items-center">
                                                    <img src="assets/img/produk/<?= $item['foto'] ?: 'default.jpg'; ?>" width="60" class="rounded-3 me-3">
                                                    <div class="fw-bold text-dark small"><?= $item['nama']; ?></div>
                                                </div>
                                            </td>
                                            <td class="small">Rp <?= number_format($item['harga'], 0, ',', '.'); ?></td>
                                            <td class="text-center">
                                                <input type="number" name="jumlah[<?= $id; ?>]" value="<?= $item['jumlah']; ?>" class="qty-input p-1" min="1">
                                            </td>
                                            <td class="text-end pe-4 fw-bold text-navy">Rp <?= number_format($subtotal, 0, ',', '.'); ?></td>
                                            <td class="pe-4">
                                                <a href="aksi_keranjang.php?hapus=<?= $id; ?>" class="text-danger"><i class="fas fa-trash-alt"></i></a>
                                            </td>
                                        </tr>
                                    <?php endforeach;
                                else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <p class="text-muted mb-3">Keranjang Tuan masih kosong.</p>
                                            <a href="index.php" class="btn btn-primary btn-sm rounded-pill px-4">Mulai Belanja</a>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                        <?php if (!empty($keranjang)): ?>
                            <div class="p-3 text-end bg-light">
                                <button type="submit" name="update_keranjang" class="btn btn-outline-navy btn-sm rounded-pill px-4">
                                    <i class="fas fa-sync-alt me-1"></i> Update Keranjang
                                </button>
                            </div>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card summary-card border-0 shadow-sm p-4 animate__animated animate__fadeInRight">
                <h5 class="fw-800 mb-4">Ringkasan Pesanan</h5>
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Total Harga</span>
                    <span class="fw-bold">Rp <?= number_format($total_akhir, 0, ',', '.'); ?></span>
                </div>
                <div class="d-flex justify-content-between mb-4">
                    <span class="text-muted">Biaya Layanan</span>
                    <span class="text-success fw-bold">Gratis</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between mb-4">
                    <span class="fw-bold">Total Bayar</span>
                    <h4 class="fw-800 text-primary">Rp <?= number_format($total_akhir, 0, ',', '.'); ?></h4>
                </div>

                <a href="checkout.php" class="btn btn-navy w-100 py-3 fw-bold <?= empty($keranjang) ? 'disabled' : ''; ?>" style="border-radius: 15px;">
                    LANJUT KE PEMBAYARAN <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>