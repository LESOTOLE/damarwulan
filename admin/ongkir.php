<?php
session_start();
require_once '../config/koneksi.php';

if (!isset($conn)) {
    die("Error: Variabel \$conn tidak ditemukan. Periksa file config/koneksi.php Tuan.");
}

include 'header_admin.php';
?>

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="col">
            <h2 class="fw-800 text-navy mb-1">Ongkir <span class="text-primary">Area</span></h2>
            <div class="d-flex align-items-center">
                <span class="badge bg-soft-primary text-primary px-3 py-2 me-2" style="border-radius: 10px;">
                    <i class="fas fa-map-marked-alt me-1"></i> Area Pengiriman
                </span>
                <p class="text-muted small mb-0">Kelola tarif dasar dan harga tambahan per area.</p>
            </div>
        </div>
        <button class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold" data-bs-toggle="modal" data-bs-target="#modalTambahArea">
            <i class="fas fa-plus me-2"></i> Tambah Area
        </button>
    </div>

    <div class="col-lg-12">
        <div class="card card-table border-0 shadow-sm">
            <div class="card-body p-4">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr class="text-muted small text-uppercase">
                            <th class="p-3">Nama Area</th>
                            <th class="p-3 text-end">Harga Dasar (Rp)</th>
                            <th class="p-3 text-end">Batas Berat Dasar (Gram)</th>
                            <th class="p-3 text-end">Harga Tambahan /Kg (Rp)</th>
                            <th class="p-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $q = mysqli_query($conn, "SELECT * FROM ongkir_area ORDER BY id_area ASC");
                        while ($area = mysqli_fetch_assoc($q)):
                        ?>
                            <tr>
                                <td class="p-3 fw-bold"><?= $area['nama_area']; ?></td>
                                <td class="p-3 text-end"><?= number_format($area['harga_dasar'], 0, ',', '.'); ?></td>
                                <td class="p-3 text-end"><?= number_format($area['batas_berat_gram'], 0, ',', '.'); ?> g</td>
                                <td class="p-3 text-end"><?= number_format($area['harga_per_kg_tambahan'], 0, ',', '.'); ?></td>
                                <td class="p-3 text-center">
                                    <button class="btn btn-light btn-sm rounded-3 text-primary" data-bs-toggle="modal" data-bs-target="#modalEditArea<?= $area['id_area']; ?>"><i class="fas fa-edit"></i> Edit</button>
                                </td>
                            </tr>

                            <!-- Modal Edit -->
                            <div class="modal fade" id="modalEditArea<?= $area['id_area']; ?>" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0" style="border-radius: 20px;">
                                        <form action="aksi_ongkir.php" method="POST">
                                            <div class="modal-header border-0 pb-0">
                                                <h6 class="fw-bold mb-0">Edit Area: <?= $area['nama_area']; ?></h6>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body p-4">
                                                <input type="hidden" name="id_area" value="<?= $area['id_area']; ?>">
                                                
                                                <div class="mb-3">
                                                    <label class="form-label small fw-bold text-muted">Nama Area</label>
                                                    <input type="text" name="nama_area" class="form-control" value="<?= $area['nama_area']; ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label small fw-bold text-muted">Harga Dasar (Rp)</label>
                                                    <input type="number" name="harga_dasar" class="form-control" value="<?= $area['harga_dasar']; ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label small fw-bold text-muted">Batas Berat Dasar (Gram)</label>
                                                    <input type="number" name="batas_berat_gram" class="form-control" value="<?= $area['batas_berat_gram']; ?>" required>
                                                </div>
                                                <div class="mb-4">
                                                    <label class="form-label small fw-bold text-muted">Harga Tambahan per Kg (Rp)</label>
                                                    <input type="number" name="harga_per_kg_tambahan" class="form-control" value="<?= $area['harga_per_kg_tambahan']; ?>" required>
                                                </div>

                                                <div class="d-flex gap-2">
                                                    <button type="submit" name="edit" class="btn btn-primary w-100 rounded-pill fw-bold">Simpan Perubahan</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambahArea" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0" style="border-radius: 20px;">
            <form action="aksi_ongkir.php" method="POST">
                <div class="modal-header border-0 pb-0">
                    <h6 class="fw-bold mb-0">Tambah Area Baru</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Nama Area</label>
                        <input type="text" name="nama_area" class="form-control" placeholder="Contoh: Luar Pulau Jawa" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Harga Dasar (Rp)</label>
                        <input type="number" name="harga_dasar" class="form-control" placeholder="Contoh: 20000" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Batas Berat Dasar (Gram)</label>
                        <input type="number" name="batas_berat_gram" class="form-control" placeholder="Contoh: 1000" required>
                        <small class="text-muted" style="font-size: 11px;">Jika berat melebihi batas ini, akan dikenakan harga tambahan per kg.</small>
                    </div>
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted">Harga Tambahan per Kg (Rp)</label>
                        <input type="number" name="harga_per_kg_tambahan" class="form-control" placeholder="Contoh: 10000" required>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" name="tambah" class="btn btn-primary w-100 rounded-pill fw-bold">Simpan Area</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'footer_admin.php'; ?>
