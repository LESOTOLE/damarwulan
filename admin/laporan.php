<?php
session_start();
require_once '../config/koneksi.php'; // 1. Koneksi harus paling atas

// 2. Cek apakah variabel $conn benar-benar ada
if (!isset($conn)) {
    die("Error: Variabel \$conn tidak ditemukan. Periksa file config/koneksi.php Tuan.");
}
if (!isset($_SESSION['id_pengguna']) || ($_SESSION['id_peran'] != 1 && $_SESSION['id_peran'] != 2)) {
    header("Location: index.php");
    exit();
}

include 'header_admin.php';

$tgl_awal = isset($_GET['tgl_awal']) ? mysqli_real_escape_string($conn, $_GET['tgl_awal']) : date('Y-m-01');
$tgl_akhir = isset($_GET['tgl_akhir']) ? mysqli_real_escape_string($conn, $_GET['tgl_akhir']) : date('Y-m-t');

// Ambil data untuk grafik
$sql_grafik = "SELECT DATE(tgl_transaksi) as tgl, SUM(total_bayar) as total_harian 
               FROM transaksi 
               WHERE status_transaksi = 'selesai' 
               AND DATE(tgl_transaksi) BETWEEN '$tgl_awal' AND '$tgl_akhir' 
               GROUP BY DATE(tgl_transaksi) 
               ORDER BY DATE(tgl_transaksi) ASC";
$res_grafik = mysqli_query($conn, $sql_grafik);

$label_grafik = [];
$data_grafik = [];
$total_pendapatan = 0;
$total_transaksi = 0;

while ($row = mysqli_fetch_assoc($res_grafik)) {
    $label_grafik[] = date('d M', strtotime($row['tgl']));
    $data_grafik[] = $row['total_harian'];
    $total_pendapatan += $row['total_harian'];
}

// Hitung total transaksi keseluruhan periode
$sql_total_trx = "SELECT COUNT(id_transaksi) as jml_trx FROM transaksi WHERE status_transaksi = 'selesai' AND DATE(tgl_transaksi) BETWEEN '$tgl_awal' AND '$tgl_akhir'";
$res_total_trx = mysqli_query($conn, $sql_total_trx);
if($res_total_trx && $row_trx = mysqli_fetch_assoc($res_total_trx)) {
    $total_transaksi = $row_trx['jml_trx'];
}

$label_grafik_json = json_encode($label_grafik);
$data_grafik_json = json_encode($data_grafik);
?>

<div class="container-fluid py-4" style="background-color: #f8f9fa;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="col">
            <h2 class="fw-bold mb-1" style="color: #2c3e50;">Laporan <span style="color: #3498db;">Pendapatan</span></h2>
            <div class="d-flex align-items-center mt-2">
                <span class="badge bg-primary text-white px-3 py-2 me-2" style="border-radius: 8px; font-weight: 500; letter-spacing: 0.5px;">
                    <i class="fas fa-boxes me-1"></i> Laporan Penjualan Suku Cadang
                </span>
                <p class="text-muted small mb-0" style="font-size: 0.9rem;">Analisis komprehensif data penjualan harian.</p>
            </div>
        </div>
        <button type="button" class="btn btn-primary shadow-sm px-4 py-2" data-bs-toggle="modal" data-bs-target="#modalCetakLaporan" style="border-radius: 8px; font-weight: 600; transition: all 0.3s ease;">
            <i class="fas fa-print me-2"></i> Cetak Laporan Resmi
        </button>
    </div>

    <!-- Ringkasan Statistik -->
    <div class="row mb-4">
        <div class="col-md-6 mb-3 mb-md-0">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 15px; background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%); color: white;">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="me-4 p-3 bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="fas fa-wallet fa-2x text-white"></i>
                    </div>
                    <div>
                        <p class="mb-1 text-white-50 fw-bold text-uppercase" style="letter-spacing: 1px; font-size: 0.8rem;">Total Pendapatan</p>
                        <h3 class="mb-0 fw-bold">Rp <?= number_format($total_pendapatan, 0, ',', '.'); ?></h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 15px; background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white;">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="me-4 p-3 bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="fas fa-shopping-cart fa-2x text-white"></i>
                    </div>
                    <div>
                        <p class="mb-1 text-white-50 fw-bold text-uppercase" style="letter-spacing: 1px; font-size: 0.8rem;">Total Transaksi Selesai</p>
                        <h3 class="mb-0 fw-bold"><?= $total_transaksi; ?> Transaksi</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Form & Chart -->
    <div class="row mb-4">
        <div class="col-lg-4 mb-4 mb-lg-0">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 15px;">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4" style="color: #2c3e50;"><i class="fas fa-filter me-2 text-primary"></i> Filter Periode</h5>
                    <form method="GET" class="d-flex flex-column h-100">
                        <div class="mb-4">
                            <label class="small fw-bold text-muted mb-2">DARI TANGGAL</label>
                            <input type="date" name="tgl_awal" class="form-control form-control-lg bg-light border-0" value="<?= $tgl_awal; ?>" style="border-radius: 10px;">
                        </div>
                        <div class="mb-4">
                            <label class="small fw-bold text-muted mb-2">SAMPAI TANGGAL</label>
                            <input type="date" name="tgl_akhir" class="form-control form-control-lg bg-light border-0" value="<?= $tgl_akhir; ?>" style="border-radius: 10px;">
                        </div>
                        <div class="mt-auto">
                            <button type="submit" class="btn btn-primary w-100 py-3 fw-bold shadow-sm" style="border-radius: 10px;">TAMPILKAN DATA</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 15px;">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4" style="color: #2c3e50;"><i class="fas fa-chart-line me-2 text-primary"></i> Grafik Pendapatan Harian</h5>
                    <div style="height: 300px; width: 100%;">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Data -->
    <div class="card border-0 shadow-sm" style="border-radius: 15px; overflow: hidden;">
        <div class="card-header bg-white border-0 p-4 pb-0">
            <h5 class="fw-bold mb-0" style="color: #2c3e50;"><i class="fas fa-list-alt me-2 text-primary"></i> Rincian Transaksi</h5>
        </div>
        <div class="card-body p-0 mt-3">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-nowrap">
                    <thead class="bg-light small fw-bold text-uppercase" style="color: #7f8c8d; letter-spacing: 0.5px;">
                        <tr>
                            <th class="ps-4 py-3 border-0">Tanggal</th>
                            <th class="border-0">Invoice</th>
                            <th class="border-0">Pelanggan</th>
                            <th class="text-end pe-4 border-0">Total Pembayaran</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT t.*, u.nama FROM transaksi t JOIN pengguna u ON t.id_pengguna = u.id_pengguna 
                                WHERE t.status_transaksi = 'selesai' AND DATE(t.tgl_transaksi) BETWEEN '$tgl_awal' AND '$tgl_akhir'
                                ORDER BY t.tgl_transaksi DESC";
                        $res = mysqli_query($conn, $sql);
                        if(mysqli_num_rows($res) > 0):
                            while ($row = mysqli_fetch_assoc($res)):
                        ?>
                                <tr>
                                    <td class="ps-4 py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light rounded-circle p-2 me-3 text-primary text-center" style="width: 40px; height: 40px;">
                                                <i class="far fa-calendar-alt"></i>
                                            </div>
                                            <span class="fw-semibold text-dark"><?= date('d M Y', strtotime($row['tgl_transaksi'])); ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-soft-primary text-primary px-2 py-1" style="font-size: 0.85rem; background-color: rgba(13, 110, 253, 0.1);">#<?= $row['id_transaksi']; ?></span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-2 bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                                <span class="fw-bold text-secondary"><?= strtoupper(substr($row['nama'], 0, 1)); ?></span>
                                            </div>
                                            <span class="fw-medium"><?= $row['nama']; ?></span>
                                        </div>
                                    </td>
                                    <td class="text-end pe-4 fw-bold text-success fs-6">
                                        Rp <?= number_format($row['total_bayar'], 0, ',', '.'); ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    <i class="fas fa-inbox fa-3x mb-3 text-light"></i>
                                    <p class="mb-0">Tidak ada data transaksi pada periode ini.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Cetak Laporan -->
<div class="modal fade" id="modalCetakLaporan" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0" style="border-radius: 20px;">
            <form action="cetak_laporan.php" method="GET" target="_blank">
                <div class="modal-header border-0 pb-0">
                    <h6 class="fw-bold mb-0">Pilih Jenis Laporan</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Dari Tanggal</label>
                        <input type="date" name="tgl_awal" class="form-control" value="<?= $tgl_awal; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Sampai Tanggal</label>
                        <input type="date" name="tgl_akhir" class="form-control" value="<?= $tgl_akhir; ?>" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted">Jenis Laporan</label>
                        <select name="jenis_laporan" class="form-select" required>
                            <option value="produk">1. Laporan Rincian Produk Terjual</option>
                            <option value="area">2. Laporan Pengiriman Berdasarkan Area</option>
                            <option value="kategori">3. Laporan Kinerja Penjualan per Kategori</option>
                            <option value="status_pesanan">4. Laporan Detail Status Pesanan Pelanggan</option>
                        </select>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold"><i class="fas fa-print me-2"></i> Cetak Sekarang</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Tambahkan Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const ctx = document.getElementById('revenueChart').getContext('2d');
    
    // Gradient untuk area chart
    let gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(52, 152, 219, 0.5)');   
    gradient.addColorStop(1, 'rgba(52, 152, 219, 0.0)');

    const chartData = {
        labels: <?= $label_grafik_json; ?>,
        datasets: [{
            label: 'Pendapatan (Rp)',
            data: <?= $data_grafik_json; ?>,
            backgroundColor: gradient,
            borderColor: '#3498db',
            borderWidth: 3,
            pointBackgroundColor: '#ffffff',
            pointBorderColor: '#3498db',
            pointBorderWidth: 2,
            pointRadius: 4,
            pointHoverRadius: 6,
            fill: true,
            tension: 0.4 // Membuat garis chart lebih smooth
        }]
    };

    const config = {
        type: 'line',
        data: chartData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(44, 62, 80, 0.9)',
                    titleFont: { size: 13 },
                    bodyFont: { size: 14, weight: 'bold' },
                    padding: 12,
                    cornerRadius: 8,
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            if (context.parsed.y !== null) {
                                label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(context.parsed.y);
                            }
                            return label;
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false,
                        drawBorder: false
                    },
                    ticks: {
                        font: {
                            family: "'Inter', sans-serif",
                            size: 11
                        },
                        color: '#95a5a6'
                    }
                },
                y: {
                    grid: {
                        color: '#f1f2f6',
                        drawBorder: false,
                        borderDash: [5, 5]
                    },
                    ticks: {
                        font: {
                            family: "'Inter', sans-serif",
                            size: 11
                        },
                        color: '#95a5a6',
                        callback: function(value, index, values) {
                            if (value >= 1000000) {
                                return 'Rp ' + (value / 1000000).toFixed(1) + 'M';
                            } else if (value >= 1000) {
                                return 'Rp ' + (value / 1000).toFixed(0) + 'K';
                            }
                            return 'Rp ' + value;
                        }
                    },
                    beginAtZero: true
                }
            },
            interaction: {
                intersect: false,
                mode: 'index',
            },
        }
    };

    new Chart(ctx, config);
});
</script>

<?php include 'footer_admin.php'; ?>