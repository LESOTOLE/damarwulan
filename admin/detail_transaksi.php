<?php
session_start();
require_once '../config/koneksi.php'; // 1. Koneksi harus paling atas

// 2. Cek apakah variabel $conn benar-benar ada
if (!isset($conn)) {
    die("Error: Variabel \$conn tidak ditemukan. Periksa file config/koneksi.php Tuan.");
}
if (!isset($_SESSION['id_pengguna'])) {
    header("Location: ../login.php");
    exit();
}

$id_t = mysqli_real_escape_string($conn, $_GET['id']);

// 1. Ambil data utama transaksi & pembeli
$query = mysqli_query($conn, "SELECT t.*, u.nama, u.email FROM transaksi t 
                              JOIN pengguna u ON t.id_pengguna = u.id_pengguna 
                              WHERE t.id_transaksi = '$id_t'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "Transaksi tidak ditemukan!";
    exit();
}

include 'header_admin.php';
?>

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2 class="fw-800 text-dark">Detail Transaksi</h2>
            <p class="text-muted">ID: <span class="text-primary fw-bold"><?= $data['id_transaksi']; ?></span></p>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="index.php" class="btn btn-light border rounded-pill px-4">
                <i class="fas fa-arrow-left me-2"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card card-custom border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">Daftar Produk</h5>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr class="text-muted small text-uppercase">
                                    <th>Produk</th>
                                    <th class="text-center">Jumlah</th>
                                    <th class="text-end">Harga</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Ambil detail item (Sesuaikan nama tabel Tuan)
                                $q_items = mysqli_query($conn, "SELECT dt.*, p.nama_produk 
                                                               FROM detail_transaksi dt 
                                                               JOIN produk p ON dt.id_produk = p.id_produk 
                                                               WHERE dt.id_transaksi = '$id_t'");
                                $grand_total = 0;
                                while ($item = mysqli_fetch_assoc($q_items)):
                                    $sub = $item['jumlah'] * $item['harga_satuan'];
                                    $grand_total += $sub;
                                ?>
                                    <tr>
                                        <td>
                                            <div class="fw-bold"><?= $item['nama_produk']; ?></div>
                                        </td>
                                        <td class="text-center"><?= $item['jumlah']; ?> Pcs</td>
                                        <td class="text-end">Rp <?= number_format($item['harga_satuan'], 0, ',', '.'); ?></td>
                                        <td class="text-end fw-bold text-navy">Rp <?= number_format($sub, 0, ',', '.'); ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold py-4">Total Bayar</td>
                                    <td class="text-end fw-800 text-danger fs-5 py-4">Rp <?= number_format($data['total_bayar'], 0, ',', '.'); ?></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card card-custom border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">Status Pesanan</h6>
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 w-100 text-uppercase mb-3">
                        <?= $data['status_transaksi']; ?>
                    </span>
                    <hr>
                    <small class="text-muted d-block">Metode Pembayaran:</small>
                    <div class="fw-bold mb-3"><?= $data['metode_bayar']; ?></div>

                    <?php if ($data['xendit_invoice_url']): ?>
                        <a href="<?= $data['xendit_invoice_url']; ?>" target="_blank" class="btn btn-navy w-100">
                            <i class="fas fa-external-link-alt me-2"></i> Cek Invoice Xendit
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card card-custom border-0 shadow-sm">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">Informasi Pelanggan</h6>
                    <div class="d-flex align-items-center mb-3">
                        <img src="https://ui-avatars.com/api/?name=<?= $data['nama']; ?>&background=random" class="rounded-circle me-3" width="40">
                        <div>
                            <div class="fw-bold"><?= $data['nama']; ?></div>
                            <small class="text-muted"><?= $data['email']; ?></small>
                        </div>
                    </div>
                    <small class="text-muted d-block">Waktu Transaksi:</small>
                    <div class="small fw-bold"><?= date('d M Y, H:i', strtotime($data['tgl_transaksi'])); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>