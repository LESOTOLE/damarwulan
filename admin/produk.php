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
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h2 class="fw-bold mb-1" style="color: #2c3e50;">Manajemen <span style="color: #3498db;">Stok</span></h2>
            <div class="d-flex align-items-center mt-2">
                <span class="badge bg-primary text-white px-3 py-2 me-2" style="border-radius: 8px; font-weight: 500; letter-spacing: 0.5px;">
                    <i class="fas fa-boxes me-1"></i> Inventaris Suku Cadang
                </span>
                <p class="text-muted small mb-0" style="font-size: 0.9rem;">Pantau dan kelola ketersediaan part AC & Radiator.</p>
            </div>
        </div>
        <div class="col-auto">
            <button class="btn btn-primary shadow-sm px-4 py-2" data-bs-toggle="modal" data-bs-target="#modalTambah" style="border-radius: 8px; font-weight: 600; transition: all 0.3s ease;">
                <i class="fas fa-plus-circle me-2"></i> TAMBAH PRODUK
            </button>
        </div>
    </div>
    
    <div class="card border-0 shadow-sm" style="border-radius: 15px; overflow: hidden;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0 text-nowrap">
                    <thead class="bg-light small fw-bold text-uppercase" style="color: #7f8c8d; letter-spacing: 0.5px;">
                        <tr>
                            <th class="ps-4 py-3 border-0">NAMA PRODUK</th>
                            <th class="border-0">KATEGORI</th>
                            <th class="border-0">BRAND</th>
                            <th class="border-0">HARGA JUAL</th>
                            <th class="text-center border-0">STOK</th>
                            <th class="text-center pe-4 border-0">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Query Join ke Kategori
                        $sql = "SELECT p.*, k.nama_kategori 
                            FROM produk p 
                            JOIN kategori k ON p.id_kategori = k.id_kategori 
                            ORDER BY p.id_produk DESC";
                        $res = mysqli_query($conn, $sql);
                        if(mysqli_num_rows($res) > 0):
                            while ($row = mysqli_fetch_assoc($res)):
                        ?>
                                <tr style="transition: all 0.2s ease;" class="table-row-hover border-bottom">
                                    <td class="ps-4 py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                <?php if(!empty($row['gambar']) && $row['gambar'] != 'default-part.jpg'): ?>
                                                    <img src="../assets/img/<?= $row['gambar']; ?>" alt="img" class="rounded shadow-sm" style="width: 45px; height: 45px; object-fit: cover; border: 1px solid #eee;">
                                                <?php else: ?>
                                                    <div class="bg-light rounded p-2 text-primary text-center d-flex align-items-center justify-content-center shadow-sm" style="width: 45px; height: 45px; border: 1px solid #eee;">
                                                        <i class="fas fa-box-open"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <span class="fw-semibold text-dark"><?= $row['nama_produk']; ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge px-2 py-1" style="font-size: 0.85rem; background-color: rgba(13, 202, 240, 0.1); color: #0dcaf0;"><?= $row['nama_kategori']; ?></span>
                                    </td>
                                    <td>
                                        <span class="fw-medium text-secondary"><?= $row['merk']; ?></span>
                                    </td>
                                    <td class="fw-bold text-success fs-6">
                                        Rp <?= number_format($row['harga'], 0, ',', '.'); ?>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge <?= ($row['stok'] < 5) ? 'bg-danger' : 'bg-success'; ?> px-3 py-2" style="border-radius: 8px;">
                                            <?= $row['stok']; ?>
                                        </span>
                                    </td>
                                    <td class="text-center pe-4">
                                        <button onclick="editProduk(<?= $row['id_produk']; ?>, '<?= htmlspecialchars(addslashes($row['nama_produk'])); ?>', <?= $row['id_kategori']; ?>, '<?= htmlspecialchars(addslashes($row['merk'])); ?>', <?= $row['harga']; ?>, <?= $row['stok']; ?>)" class="btn btn-sm btn-outline-primary border-0 me-1 btn-aksi" title="Edit" style="border-radius: 6px;">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button onclick="hapusProduk(<?= $row['id_produk']; ?>)" class="btn btn-sm btn-outline-danger border-0 btn-aksi" title="Hapus" style="border-radius: 6px;">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="fas fa-box fa-3x mb-3 text-light"></i>
                                    <p class="mb-0">Belum ada produk. Silakan tambahkan produk baru.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 20px;">
            <div class="modal-header border-0 p-4 pb-0">
                <h5 class="fw-bold" style="color: #2c3e50;"><i class="fas fa-plus-circle text-primary me-2"></i>Tambah Produk Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="aksi_produk.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="small fw-bold mb-2 text-muted">NAMA PRODUK</label>
                        <input type="text" name="nama_produk" class="form-control bg-light border-0 p-3" style="border-radius: 10px;" placeholder="Contoh: Compressor Avanza" required>
                    </div>

                    <div class="mb-3">
                        <label class="small fw-bold mb-2 text-muted">GAMBAR PRODUK</label>
                        <input type="file" name="gambar" class="form-control bg-light border-0 p-2" style="border-radius: 10px;" accept="image/*">
                    </div>

                    <div class="mb-3">
                        <label class="small fw-bold mb-2 text-muted">KATEGORI</label>
                        <select name="id_kategori" class="form-select bg-light border-0 p-3" style="border-radius: 10px;" required>
                            <option value="">Pilih Kategori...</option>
                            <?php
                            $q_kat = mysqli_query($conn, "SELECT * FROM kategori ORDER BY nama_kategori ASC");
                            while ($k = mysqli_fetch_assoc($q_kat)) {
                                echo "<option value='" . $k['id_kategori'] . "'>" . $k['nama_kategori'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="small fw-bold mb-2 text-muted">MERK / BRAND</label>
                            <input type="text" name="merk" class="form-control bg-light border-0 p-3" style="border-radius: 10px;" placeholder="Denso, Cool Gear, dll">
                        </div>
                        <div class="col-6 mb-3">
                            <label class="small fw-bold mb-2 text-muted">STOK AWAL</label>
                            <input type="number" name="stok" class="form-control bg-light border-0 p-3" style="border-radius: 10px;" value="0">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="small fw-bold mb-2 text-muted">HARGA JUAL (RP)</label>
                        <input type="number" name="harga" class="form-control bg-light border-0 p-3" style="border-radius: 10px;" placeholder="Tanpa titik/koma" required>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="submit" name="simpan_produk" class="btn btn-primary w-100 py-3 fw-bold shadow-sm" style="border-radius: 10px;">SIMPAN KE DATABASE</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="modalEdit" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 20px;">
            <div class="modal-header border-0 p-4 pb-0">
                <h5 class="fw-bold" style="color: #2c3e50;"><i class="fas fa-edit text-primary me-2"></i>Edit Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="aksi_produk.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id_produk" id="edit_id_produk">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="small fw-bold mb-2 text-muted">NAMA PRODUK</label>
                        <input type="text" name="nama_produk" id="edit_nama_produk" class="form-control bg-light border-0 p-3" style="border-radius: 10px;" required>
                    </div>

                    <div class="mb-3">
                        <label class="small fw-bold mb-2 text-muted">GANTI GAMBAR (Opsional)</label>
                        <input type="file" name="gambar" class="form-control bg-light border-0 p-2" style="border-radius: 10px;" accept="image/*">
                        <small class="text-muted" style="font-size: 0.75rem;">Biarkan kosong jika tidak ingin mengubah gambar.</small>
                    </div>

                    <div class="mb-3">
                        <label class="small fw-bold mb-2 text-muted">KATEGORI</label>
                        <select name="id_kategori" id="edit_id_kategori" class="form-select bg-light border-0 p-3" style="border-radius: 10px;" required>
                            <option value="">Pilih Kategori...</option>
                            <?php
                            $q_kat = mysqli_query($conn, "SELECT * FROM kategori ORDER BY nama_kategori ASC");
                            while ($k = mysqli_fetch_assoc($q_kat)) {
                                echo "<option value='" . $k['id_kategori'] . "'>" . $k['nama_kategori'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="small fw-bold mb-2 text-muted">MERK / BRAND</label>
                            <input type="text" name="merk" id="edit_merk" class="form-control bg-light border-0 p-3" style="border-radius: 10px;">
                        </div>
                        <div class="col-6 mb-3">
                            <label class="small fw-bold mb-2 text-muted">STOK</label>
                            <input type="number" name="stok" id="edit_stok" class="form-control bg-light border-0 p-3" style="border-radius: 10px;">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="small fw-bold mb-2 text-muted">HARGA JUAL (RP)</label>
                        <input type="number" name="harga" id="edit_harga" class="form-control bg-light border-0 p-3" style="border-radius: 10px;" required>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="submit" name="edit_produk" class="btn btn-primary w-100 py-3 fw-bold shadow-sm" style="border-radius: 10px;">SIMPAN PERUBAHAN</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Styling hover table row
    document.querySelectorAll('.table-row-hover').forEach(row => {
        row.addEventListener('mouseenter', () => {
            row.style.backgroundColor = '#f8f9fa';
            row.style.transform = 'scale(1.005)';
        });
        row.addEventListener('mouseleave', () => {
            row.style.backgroundColor = '';
            row.style.transform = 'scale(1)';
        });
    });

    // Fungsi untuk menampilkan modal Edit
    function editProduk(id, nama, kategori, merk, harga, stok) {
        document.getElementById('edit_id_produk').value = id;
        document.getElementById('edit_nama_produk').value = nama;
        document.getElementById('edit_id_kategori').value = kategori;
        document.getElementById('edit_merk').value = merk;
        document.getElementById('edit_harga').value = harga;
        document.getElementById('edit_stok').value = stok;
        
        var modalEdit = new bootstrap.Modal(document.getElementById('modalEdit'));
        modalEdit.show();
    }

    // Fungsi Hapus dengan SweetAlert2
    function hapusProduk(id) {
        Swal.fire({
            title: 'Yakin ingin menghapus?',
            text: "Data produk ini akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e74c3c',
            cancelButtonColor: '#95a5a6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            background: '#ffffff',
            borderRadius: '15px',
            customClass: {
                confirmButton: 'btn btn-danger px-4 py-2 me-2',
                cancelButton: 'btn btn-secondary px-4 py-2'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'aksi_produk.php?hapus=' + id;
            }
        })
    }
</script>

<style>
.btn-aksi {
    transition: all 0.2s ease;
}
.btn-aksi:hover {
    transform: translateY(-2px);
}
.btn-outline-primary:hover {
    background-color: #3498db !important;
    color: white !important;
}
.btn-outline-danger:hover {
    background-color: #e74c3c !important;
    color: white !important;
}
</style>

<?php include 'footer_admin.php'; ?>