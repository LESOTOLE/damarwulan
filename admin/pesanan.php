<?php
session_start();
require_once '../config/koneksi.php';

if (!isset($conn)) {
    die("Error: Variabel \$conn tidak ditemukan. Periksa file config/koneksi.php Tuan.");
}
// Cek Koneksi
if (!$conn) {
    die("Error: Koneksi database gagal.");
}

// Proteksi: Owner (1) & Admin (2) boleh akses
if (!isset($_SESSION['id_pengguna']) || ($_SESSION['id_peran'] != 1 && $_SESSION['id_peran'] != 2)) {
    header("Location: ../login.php");
    exit();
}

include 'header_admin.php';
?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <h2 class="fw-800 text-navy mb-1">Kelola <span class="text-primary">Pesanan</span></h2>
            <div class="d-flex align-items-center">
                <span class="badge bg-soft-primary text-primary px-3 py-2 me-2" style="border-radius: 10px; background-color: #eef2ff;">
                    <i class="fas fa-boxes me-1"></i> Kelola Pesanan Pelanggan
                </span>
                <p class="text-muted small mb-0">Pantau pembayaran dan proses pengiriman barang secara real-time.</p>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 20px; overflow: hidden;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted small">
                        <tr>
                            <th class="ps-4 py-3" style="width: 150px;">ID TRANS</th>
                            <th>PELANGGAN</th>
                            <th>TOTAL</th>
                            <th>STATUS</th>
                            <th class="text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT t.*, u.nama FROM transaksi t JOIN pengguna u ON t.id_pengguna = u.id_pengguna ORDER BY t.tgl_transaksi DESC";
                        $query = mysqli_query($conn, $sql);

                        while ($row = mysqli_fetch_assoc($query)):
                            $s = $row['status_transaksi'];

                            // Logika Warna Badge
                            if ($s == 'pending') {
                                $badge = "bg-warning";
                            } elseif ($s == 'dibayar') {
                                $badge = "bg-success";
                            } elseif ($s == 'dikirim') {
                                $badge = "bg-primary";
                            } elseif ($s == 'selesai') {
                                $badge = "bg-dark";
                            } else {
                                $badge = "bg-secondary";
                            }
                        ?>
                            <tr>
                                <td class="ps-4">
                                    <span class="fw-bold text-navy">#<?= $row['id_transaksi']; ?></span>
                                    <div class="text-muted" style="font-size: 11px;"><?= date('d M Y', strtotime($row['tgl_transaksi'])); ?></div>
                                </td>
                                <td>
                                    <div class="fw-bold"><?= $row['nama']; ?></div>
                                    <div class="text-muted small"><?= $row['metode_bayar']; ?></div>
                                </td>
                                <td class="fw-bold text-navy">Rp <?= number_format($row['total_bayar'], 0, ',', '.'); ?></td>
                                <td>
                                    <span class="badge <?= $badge; ?> rounded-pill px-3 py-2 text-uppercase" style="font-size: 9px; letter-spacing: 0.5px;">
                                        <?= $s; ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="detail_transaksi.php?id=<?= $row['id_transaksi']; ?>" class="btn btn-sm btn-light border text-primary px-3" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <?php if ($s == 'dibayar'): ?>
                                            <button type="button" class="btn btn-sm btn-primary px-3 btn-proses-kirim" style="background-color: #1a237e;" data-id="<?= $row['id_transaksi']; ?>">
                                                <i class="fas fa-truck me-1"></i> Kirim
                                            </button>
                                            <form id="form-kirim-<?= $row['id_transaksi']; ?>" action="aksi_pesanan.php" method="POST" style="display: none;">
                                                <input type="hidden" name="id_transaksi" value="<?= $row['id_transaksi']; ?>">
                                                <input type="hidden" name="proses_kirim" value="1">
                                                <input type="hidden" name="nomor_resi" value="">
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const buttons = document.querySelectorAll('.btn-proses-kirim');
    buttons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const idTransaksi = this.getAttribute('data-id');
            Swal.fire({
                title: 'Masukkan Nomor Resi',
                input: 'text',
                inputLabel: 'Nomor Resi / Keterangan Ekspedisi',
                inputPlaceholder: 'Contoh: JNE - 0123456789',
                showCancelButton: true,
                confirmButtonColor: '#1a237e',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Kirim Pesanan',
                cancelButtonText: 'Batal',
                inputValidator: (value) => {
                    if (!value) {
                        return 'Nomor Resi wajib diisi! (Ketik "Kurir Toko" jika manual)';
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById(`form-kirim-${idTransaksi}`);
                    form.querySelector('input[name="nomor_resi"]').value = result.value;
                    form.submit();
                }
            });
        });
    });
});
</script>

<?php include 'footer_admin.php'; ?>