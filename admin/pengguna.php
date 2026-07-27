<?php
session_start();
require_once '../config/koneksi.php';

if (!isset($conn)) {
    die("Error: Variabel \$conn tidak ditemukan. Periksa file config/koneksi.php Tuan.");
}
if (!isset($_SESSION['id_pengguna']) || $_SESSION['id_peran'] != 1) {
    header("Location: ../login.php");
    exit();
}

include 'header_admin.php';
?>

<div class="container-fluid">
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h2 class="fw-800 text-navy mb-1">Manajemen<span class="text-primary"> Pengguna</span></h2>
            <div class="d-flex align-items-center">
                <span class="badge bg-soft-primary text-primary px-3 py-2 me-2" style="border-radius: 10px;">
                    <i class="fas fa-boxes me-1"></i> Kelola Pengguna Sistem
                </span>
                <p class="text-muted small mb-0">Kelola data akses Pemilik, Admin, dan Pelanggan.</p>
            </div>
        </div>
        <div class="col-md-6 text-md-end">
            <button class="btn btn-navy py-2 px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
                <i class="fas fa-plus me-2"></i> Tambah Pengguna
            </button>
        </div>
    </div>

    <div class="card card-custom">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr class="text-muted small">
                            <th class="ps-4 py-3">PENGGUNA</th>
                            <th>EMAIL</th>
                            <th>ROLE</th>
                            <th class="text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = mysqli_query($conn, "SELECT p.*, r.nama_peran FROM pengguna p JOIN peran r ON p.id_peran = r.id_peran ORDER BY p.id_peran ASC, p.nama ASC");
                        while ($row = mysqli_fetch_assoc($query)):
                        ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-dark"><?= $row['nama']; ?></div>
                                </td>
                                <td><?= $row['email']; ?></td>
                                <td>
                                    <?php
                                    // Logika Warna Badge Berdasarkan Nama Peran dari DB
                                    $peran_db = strtolower($row['nama_peran']);
                                    if ($peran_db == 'pemilik' || $row['id_peran'] == 1) {
                                        $warna = 'bg-danger';
                                        $icon = 'fa-crown';
                                    } elseif ($peran_db == 'admin') {
                                        $warna = 'bg-success';
                                        $icon = 'fa-user-shield';
                                    } else {
                                        $warna = 'bg-primary';
                                        $icon = 'fa-user';
                                    }
                                    ?>
                                    <span class="badge <?= $warna; ?> rounded-pill px-3 py-2 text-uppercase" style="font-size: 10px;">
                                        <i class="fas <?= $icon; ?> me-1"></i> <?= $row['nama_peran']; ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-light text-primary border" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $row['id_pengguna']; ?>">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <?php if ($row['id_pengguna'] != $_SESSION['id_pengguna']): ?>
                                        <button class="btn btn-sm btn-light text-danger border" onclick="hapusUser(<?= $row['id_pengguna']; ?>, '<?= $row['nama']; ?>')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>

                            <div class="modal fade" id="modalEdit<?= $row['id_pengguna']; ?>" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow" style="border-radius: 20px;">
                                        <form action="aksi_pengguna.php" method="POST">
                                            <div class="modal-header border-0 p-4">
                                                <h5 class="fw-bold">Update Data User</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body p-4 pt-0">
                                                <input type="hidden" name="id_pengguna" value="<?= $row['id_pengguna']; ?>">
                                                <div class="mb-3">
                                                    <label class="form-label small fw-bold">Nama Lengkap</label>
                                                    <input type="text" name="nama" class="form-control" value="<?= $row['nama']; ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label small fw-bold">Email</label>
                                                    <input type="email" name="email" class="form-control" value="<?= $row['email']; ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label small fw-bold">Peran/Role</label>
                                                    <select name="id_peran" class="form-select">
                                                        <?php
                                                        // Ambil semua role yang ada di database
                                                        $q_role = mysqli_query($conn, "SELECT * FROM peran ORDER BY id_peran ASC");
                                                        while ($r = mysqli_fetch_assoc($q_role)):
                                                        ?>
                                                            <option value="<?= $r['id_peran']; ?>" <?= ($row['id_peran'] == $r['id_peran']) ? 'selected' : ''; ?>>
                                                                <?= $r['nama_peran']; ?>
                                                            </option>
                                                        <?php endwhile; ?>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label small fw-bold">Password Baru (Opsional)</label>
                                                    <input type="password" name="password" class="form-control" placeholder="Isi jika ingin ganti">
                                                </div>
                                            </div>
                                            <div class="modal-footer border-0 p-4">
                                                <button type="submit" name="edit" class="btn btn-navy w-100 py-2">Update User</button>
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

<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 20px;">
            <form action="aksi_pengguna.php" method="POST">
                <div class="modal-header border-0 p-4">
                    <h5 class="fw-bold">Tambah Pengguna Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4 pt-0">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control" placeholder="Nama Lengkap" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="email@contoh.com" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Role</label>
                        <select name="id_peran" class="form-select">
                            <option value="2">Pelanggan</option>
                            <option value="1">Pemilik (Owner)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4">
                    <button type="submit" name="tambah" class="btn btn-navy w-100 py-2">Simpan User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function hapusUser(id, nama) {
        Swal.fire({
            title: 'Hapus ' + nama + '?',
            text: "Data akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d32f2f',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'aksi_pengguna.php?hapus=' + id;
            }
        })
    }

    const params = new URLSearchParams(window.location.search);
    if (params.get('status') === 'sukses') {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Data telah diperbarui.',
            timer: 2000,
            showConfirmButton: false
        });
    }
</script>
</body>

</html>