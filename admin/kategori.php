<?php
session_start();
require_once '../config/koneksi.php'; // 1. Koneksi harus paling atas

// 2. Cek apakah variabel $conn benar-benar ada
if (!isset($conn)) {
    die("Error: Variabel \$conn tidak ditemukan. Periksa file config/koneksi.php Tuan.");
}

// 3. Baru panggil include yang lain
include 'header_admin.php';
?>

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="col">
            <h2 class="fw-800 text-navy mb-1">Kategori <span class="text-primary">Produk</span></h2>
            <div class="d-flex align-items-center">
                <span class="badge bg-soft-primary text-primary px-3 py-2 me-2" style="border-radius: 10px;">
                    <i class="fas fa-boxes me-1"></i> Kategori Suku Cadang
                </span>
                <p class="text-muted small mb-0">Kelola pengelompokan suku cadang Tuan.</p>
            </div>
        </div>
        <button class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold" data-bs-toggle="modal" data-bs-target="#modalKategori">
            <i class="fas fa-plus me-2"></i> Tambah Kategori
        </button>
    </div>

    <div class="col-lg-6">
        <div class="card card-table border-0 shadow-sm">
            <div class="card-body p-4">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr class="text-muted small text-uppercase">
                            <th class="p-3">Nama Kategori</th>
                            <th class="p-3 text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $q = mysqli_query($conn, "SELECT * FROM kategori ORDER BY nama_kategori ASC");
                        while ($kat = mysqli_fetch_assoc($q)):
                        ?>
                            <tr>
                                <td class="p-3 fw-bold"><?= $kat['nama_kategori']; ?></td>
                                <td class="p-3 text-end">
                                    <a href="aksi_kategori.php?hapus=<?= $kat['id_kategori']; ?>" class="btn btn-light btn-sm rounded-3 text-danger"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalKategori" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0" style="border-radius: 20px;">
            <form action="aksi_kategori.php" method="POST">
                <div class="modal-body p-4">
                    <h6 class="fw-bold mb-3">Nama Kategori Baru</h6>
                    <input type="text" name="nama_kategori" class="form-control mb-3" placeholder="Contoh: Suspensi" required>
                    <div class="d-flex gap-2">
                        <button type="submit" name="tambah" class="btn btn-primary w-100 rounded-pill fw-bold">Simpan</button>
                        <button type="button" class="btn btn-light w-100 rounded-pill fw-bold" data-bs-dismiss="modal">Batal</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'footer_admin.php'; ?>