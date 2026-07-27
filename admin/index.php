<?php
session_start();
require_once '../config/koneksi.php'; // 1. Koneksi harus paling atas

// 2. Cek apakah variabel $conn benar-benar ada
if (!isset($conn)) {
    die("Error: Variabel \$conn tidak ditemukan. Periksa file config/koneksi.php Tuan.");
}
// PROTEKSI HALAMAN
if (!isset($_SESSION['id_pengguna']) || ($_SESSION['id_peran'] != 1 && $_SESSION['id_peran'] != 2)) {
    // Tendang ke halaman login atau katalog
    header("Location: ../login.php");
    exit();
}
// 1. Total Penjualan (Hanya yang statusnya 'selesai')
$q_penjualan = mysqli_query($conn, "SELECT SUM(total_bayar) as total FROM transaksi WHERE status_transaksi = 'selesai'");
$total_penjualan = mysqli_fetch_assoc($q_penjualan)['total'] ?? 0;

// 2. Pesanan Baru (Hanya yang statusnya 'pending')
$q_pending = mysqli_query($conn, "SELECT COUNT(*) as jml FROM transaksi WHERE status_transaksi = 'pending'");
$total_pending = mysqli_fetch_assoc($q_pending)['jml'];

// 3. Stok Menipis (Tetap dari tabel produk)
$q_stok = mysqli_query($conn, "SELECT COUNT(*) as jml FROM produk WHERE stok < 5");
$total_stok_rendah = mysqli_fetch_assoc($q_stok)['jml'] ?? 0;
include 'header_admin.php';
?>

<style>
    .top-nav {
        background: #fff;
        padding: 20px 30px;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.02);
        margin-bottom: 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    .stat-card {
        background: #fff;
        padding: 25px;
        border-radius: 24px;
        border: none;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        height: 100%;
    }

    .stat-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 45px rgba(0, 0, 0, 0.08);
    }

    .icon-box {
        width: 55px;
        height: 55px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .fw-800 {
        font-weight: 800;
    }
</style>

<div class="top-nav">
    <div>
        <h5 class="fw-800 mb-0">Overview Dashboard</h5>
        <small class="text-muted">Status Sistem: <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3">Xendit Sandbox Active</span></small>
    </div>
    <div class="d-flex align-items-center">
        <div class="text-end me-3">
            <div class="fw-bold"><?= $_SESSION['nama']; ?></div>
            <small class="text-muted" style="font-size: 10px;">ID: <?= $_SESSION['id_pengguna']; ?></small>
        </div>
        <img src="https://ui-avatars.com/api/?name=<?= $_SESSION['nama']; ?>&background=1a237e&color=fff" class="rounded-circle shadow-sm" width="45">
    </div>
</div>

<div class="row g-4 mb-5">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="d-flex align-items-center mb-3">
                <div class="icon-box bg-primary text-white shadow-sm me-3">
                    <i class="fas fa-wallet"></i>
                </div>
                <small class="text-muted fw-bold text-uppercase" style="letter-spacing: 1px; font-size: 11px;">Total Penjualan</small>
            </div>
            <h3 class="fw-800 mb-0">Rp <?= number_format($total_penjualan, 0, ',', '.'); ?></h3>
        </div>
    </div>

    <div class="col-md-4">
        <div class="stat-card">
            <div class="d-flex align-items-center mb-3">
                <div class="icon-box bg-success text-white shadow-sm me-3">
                    <i class="fas fa-shopping-bag"></i>
                </div>
                <small class="text-muted fw-bold text-uppercase" style="letter-spacing: 1px; font-size: 11px;">Pesanan Baru</small>
            </div>
            <h3 class="fw-800 mb-0"><?= $total_pending; ?> Order</h3>
        </div>
    </div>

    <div class="col-md-4">
        <div class="stat-card border-start border-danger border-4">
            <div class="d-flex align-items-center mb-3">
                <div class="icon-box bg-danger text-white shadow-sm me-3">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <small class="text-muted fw-bold text-uppercase" style="letter-spacing: 1px; font-size: 11px;">Stok Menipis</small>
            </div>
            <h3 class="fw-800 mb-0"><?= $total_stok_rendah; ?> Item</h3>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm" style="border-radius: 24px;">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-bold mb-0">Transaksi Terbaru</h5>
            <a href="pesanan.php" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm">Lihat Semua</a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr class="text-muted small text-uppercase">
                        <th class="p-3 border-0">ID Transaksi</th>
                        <th class="p-3 border-0">Pelanggan</th>
                        <th class="p-3 border-0">Metode Bayar</th>
                        <th class="p-3 border-0">Total</th>
                        <th class="p-3 border-0">Status</th>
                        <th class="p-3 border-0 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Query Join ke tabel pengguna untuk ambil Nama
                    $sql_transaksi = "SELECT t.*, u.nama 
                      FROM transaksi t 
                      JOIN pengguna u ON t.id_pengguna = u.id_pengguna 
                      ORDER BY t.tgl_transaksi DESC 
                      LIMIT 5";
                    $query_transaksi = mysqli_query($conn, $sql_transaksi);

                    if (mysqli_num_rows($query_transaksi) > 0) {
                        while ($row = mysqli_fetch_assoc($query_transaksi)) {

                            // Logika Warna Badge berdasarkan ENUM di DB Tuan
                            $status = $row['status_transaksi'];
                            switch ($status) {
                                case 'pending':
                                    $badge = "bg-warning-subtle text-warning border-warning-subtle";
                                    break;
                                case 'dibayar':
                                    $badge = "bg-info-subtle text-info border-info-subtle";
                                    break;
                                case 'dikirim':
                                    $badge = "bg-primary-subtle text-primary border-primary-subtle";
                                    break;
                                case 'selesai':
                                    $badge = "bg-success-subtle text-success border-success-subtle";
                                    break;
                                default:
                                    $badge = "bg-secondary-subtle text-secondary border-secondary-subtle";
                            }
                    ?>
                            <tr>
                                <td class="p-3 fw-bold"><?= $row['id_transaksi']; ?></td>
                                <td class="p-3"><?= $row['nama']; ?></td>
                                <td class="p-3">
                                    <span class="badge bg-light text-dark border px-3">
                                        <i class="fas fa-credit-card me-1 text-primary"></i> <?= $row['metode_bayar'] ?? 'Belum Pilih'; ?>
                                    </span>
                                </td>
                                <td class="p-3 fw-800 text-navy">Rp <?= number_format($row['total_bayar'], 0, ',', '.'); ?></td>
                                <td class="p-3">
                                    <span class="badge <?= $badge; ?> border px-3 py-2 text-uppercase" style="font-size: 10px;">
                                        <?= $status; ?>
                                    </span>
                                </td>
                                <td class="p-3 text-center">
                                    <a href="detail_transaksi.php?id=<?= $row['id_transaksi']; ?>" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                    <?php
                        }
                    } else {
                        echo "<tr><td colspan='6' class='text-center py-4 text-muted'>Belum ada transaksi di tabel Damar.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('login') === 'sukses') {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: 'Selamat Datang Kembali, Tuan!',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
    }
</script>

<?php include 'footer_admin.php'; // Pastikan Tuan menutup tag div #content di sini 
?>