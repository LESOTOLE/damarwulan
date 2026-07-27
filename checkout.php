<?php
session_start();
require 'config/koneksi.php';

// Proteksi: Harus login
if (!isset($_SESSION['id_pengguna'])) {
    header("Location: login.php?status=belum_login");
    exit();
}

// Proteksi: Keranjang tidak kosong
if (empty($_SESSION['keranjang'])) {
    header("Location: index.php");
    exit();
}

$id_user = $_SESSION['id_pengguna'];

// Ambil data alamat dari profil untuk ditampilkan (Hanya Lihat)
$query_user = mysqli_query($conn, "
    SELECT p.nama, p.alamat, p.no_telepon, p.id_area, o.nama_area, o.harga_dasar, o.batas_berat_gram, o.harga_per_kg_tambahan
    FROM pengguna p
    LEFT JOIN ongkir_area o ON p.id_area = o.id_area
    WHERE p.id_pengguna = '$id_user'
");
$user = mysqli_fetch_assoc($query_user);

include 'includes/header.php';
include 'includes/navbar.php';
?>

<div class="container py-5 mt-4">
    <div class="row">
        <div class="col-lg-8 mb-4">
            <h3 class="fw-800 text-navy mb-4">Tinjau <span class="text-primary">Pesanan</span></h3>

            <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px;">
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="text-muted small uppercase">
                                <tr>
                                    <th>Produk</th>
                                    <th class="text-center">Jumlah</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $total_bayar = 0;
                                $total_berat_gram = 0;
                                
                                // Ambil ID produk yang ada di keranjang untuk batch query berat
                                $product_ids = array_keys($_SESSION['keranjang']);
                                $berat_produk_map = [];
                                if (!empty($product_ids)) {
                                    $in_ids = implode(',', array_map('intval', $product_ids));
                                    $q_berat = mysqli_query($conn, "SELECT id_produk, berat_gram FROM produk WHERE id_produk IN ($in_ids)");
                                    while ($row_berat = mysqli_fetch_assoc($q_berat)) {
                                        $berat_produk_map[$row_berat['id_produk']] = $row_berat['berat_gram'];
                                    }
                                }

                                foreach ($_SESSION['keranjang'] as $id => $item) :
                                    $subtotal = $item['harga'] * $item['jumlah'];
                                    $total_bayar += $subtotal;
                                    
                                    // Ambil berat produk dari array mapping
                                    $berat = isset($berat_produk_map[$id]) ? $berat_produk_map[$id] : 0;
                                    $total_berat_gram += ($berat * $item['jumlah']);
                                ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="fw-bold text-navy"><?= $item['nama']; ?></div>
                                            </div>
                                            <small class="text-muted">Rp <?= number_format($item['harga'], 0, ',', '.'); ?></small>
                                        </td>
                                        <td class="text-center fw-bold"><?= $item['jumlah']; ?></td>
                                        <td class="text-end fw-bold text-primary">Rp <?= number_format($subtotal, 0, ',', '.'); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0"><i class="fas fa-map-marker-alt text-danger me-2"></i> Alamat Pengiriman</h5>
                        <a href="alamat.php" class="btn btn-sm btn-outline-primary rounded-pill px-3">Ubah</a>
                    </div>

                    <?php if (empty($user['alamat']) || empty($user['id_area'])) : ?>
                        <div class="alert alert-warning border-0 rounded-3">
                            <i class="fas fa-exclamation-triangle me-2"></i> Alamat atau Zona Pengiriman belum lengkap. <a href="alamat.php" class="fw-bold text-decoration-none">Klik di sini untuk mengisi.</a>
                        </div>
                    <?php else : ?>
                        <div class="p-3 bg-light rounded-3">
                            <div class="fw-bold mb-1"><?= $user['nama']; ?> <span class="text-muted fw-normal">| <?= $user['no_telepon']; ?></span></div>
                            <p class="text-muted mb-0 small"><?= $user['alamat']; ?></p>
                            <div class="mt-2 text-primary small fw-bold"><i class="fas fa-map-marker-alt me-1"></i> Zona: <?= $user['nama_area']; ?></div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm sticky-top" style="border-radius: 20px; top: 100px; z-index: 10;">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">Ringkasan Pembayaran</h5>

                    <?php
                    // Hitung Ongkir
                    $ongkir = 0;
                    if (!empty($user['id_area'])) {
                        $ongkir = $user['harga_dasar'];
                        if ($total_berat_gram > $user['batas_berat_gram']) {
                            $extra_gram = $total_berat_gram - $user['batas_berat_gram'];
                            $extra_kg = ceil($extra_gram / 1000);
                            $ongkir += ($extra_kg * $user['harga_per_kg_tambahan']);
                        }
                    }
                    $total_tagihan = $total_bayar + $ongkir;
                    ?>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Total Harga (<?= count($_SESSION['keranjang']); ?> Produk)</span>
                        <span class="fw-bold text-navy">Rp <?= number_format($total_bayar, 0, ',', '.'); ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Total Berat</span>
                        <span class="fw-bold text-navy"><?= number_format($total_berat_gram / 1000, 2, ',', '.'); ?> Kg</span>
                    </div>
                    <div class="d-flex justify-content-between mb-4">
                        <span class="text-muted">Biaya Pengiriman <?= !empty($user['nama_area']) ? '('.$user['nama_area'].')' : '' ?></span>
                        <?php if (empty($user['id_area'])): ?>
                            <span class="text-danger fw-bold">Pilih Zona Alamat</span>
                        <?php else: ?>
                            <span class="fw-bold text-navy">Rp <?= number_format($ongkir, 0, ',', '.'); ?></span>
                        <?php endif; ?>
                    </div>

                    <hr class="dashed">

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <span class="fw-bold">Total Tagihan</span>
                        <h4 class="fw-800 text-primary mb-0">Rp <?= number_format($total_tagihan, 0, ',', '.'); ?></h4>
                    </div>

                    <form action="proses_checkout.php" method="POST">
                        <div class="mb-4">
                            <label class="small fw-bold text-muted mb-2">Catatan Pesanan (Opsional)</label>
                            <textarea name="catatan" class="form-control border-0 bg-light" rows="2" placeholder="Contoh: Titip satpam..." style="border-radius: 12px;"></textarea>
                        </div>

                        <?php if (!empty($user['alamat']) && !empty($user['id_area'])) : ?>
                            <button type="submit" class="btn btn-primary w-100 py-3 fw-bold shadow-sm" style="border-radius: 15px;">
                                BAYAR SEKARANG <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        <?php else : ?>
                            <button type="button" class="btn btn-secondary w-100 py-3 fw-bold disabled" style="border-radius: 15px;">
                                LENGKAPI ALAMAT & ZONA DULU
                            </button>
                        <?php endif; ?>
                    </form>

                    <div class="mt-4 text-center">
                        <img src="https://xendit.co/wp-content/uploads/2021/04/Logo-Xendit.png" width="80" alt="Xendit Payment" style="opacity: 0.5;">
                        <p class="text-muted mt-2" style="font-size: 10px;">Pembayaran aman dan terenkripsi melalui Xendit.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>