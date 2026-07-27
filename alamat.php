<?php
session_start();
require 'config/koneksi.php';

// Pastikan pelanggan sudah login
if (!isset($_SESSION['id_pengguna'])) {
    header("Location: login.php");
    exit();
}

$id_user = $_SESSION['id_pengguna'];
$pesan_sukses = '';
$pesan_error = '';

// Proses Simpan Data
if (isset($_POST['simpan_alamat'])) {
    // Gunakan real_escape_string untuk keamanan (mencegah SQL Injection)
    $no_telp = mysqli_real_escape_string($conn, $_POST['no_telepon']);
    $alamat  = mysqli_real_escape_string($conn, $_POST['alamat']);
    $id_area = mysqli_real_escape_string($conn, $_POST['id_area']);

    // Update data ke tabel pengguna
    $update = mysqli_query($conn, "UPDATE pengguna SET no_telepon = '$no_telp', alamat = '$alamat', id_area = '$id_area' WHERE id_pengguna = '$id_user'");

    if ($update) {
        $pesan_sukses = "Data alamat berhasil diperbarui!";
    } else {
        $pesan_error = "Gagal memperbarui data. Silakan coba lagi.";
    }
}

// Ambil data pelanggan saat ini untuk ditampilkan di dalam form
$query = mysqli_query($conn, "SELECT nama, email, no_telepon, alamat, id_area FROM pengguna WHERE id_pengguna = '$id_user'");
$user = mysqli_fetch_assoc($query);

include 'includes/header.php';
include 'includes/navbar.php';
?>

<div class="container py-5 mt-4">
    <div class="row justify-content-center animate__animated animate__fadeInUp">
        <div class="col-md-8 col-lg-6">

            <div class="d-flex align-items-center mb-4">
                <a href="index.php" class="btn btn-light rounded-circle shadow-sm me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h3 class="fw-800 text-navy mb-0">Alamat <span class="text-primary">Pengiriman</span></h3>
            </div>

            <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                <div class="card-body p-4 p-md-5">

                    <?php if ($pesan_sukses): ?>
                        <div class="alert alert-success border-0 rounded-3 shadow-sm mb-4">
                            <i class="fas fa-check-circle me-2"></i> <?= $pesan_sukses; ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($pesan_error): ?>
                        <div class="alert alert-danger border-0 rounded-3 shadow-sm mb-4">
                            <i class="fas fa-exclamation-circle me-2"></i> <?= $pesan_error; ?>
                        </div>
                    <?php endif; ?>

                    <form action="" method="POST">
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label class="text-muted small fw-bold mb-1">Nama Penerima</label>
                                <input type="text" class="form-control bg-light border-0" value="<?= $user['nama']; ?>" readonly style="border-radius: 10px;">
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small fw-bold mb-1">Email</label>
                                <input type="email" class="form-control bg-light border-0" value="<?= $user['email']; ?>" readonly style="border-radius: 10px;">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark">Nomor WhatsApp / Telepon <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0" style="border-radius: 10px 0 0 10px;"><i class="fab fa-whatsapp text-success"></i></span>
                                <input type="number" name="no_telepon" class="form-control border-start-0" placeholder="Contoh: 08123456789" value="<?= $user['no_telepon']; ?>" required style="border-radius: 0 10px 10px 0;">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark">Zona Pengiriman <span class="text-danger">*</span></label>
                            <select name="id_area" class="form-select bg-light" required style="border-radius: 10px; border: none; padding: 10px;">
                                <option value="">-- Pilih Zona --</option>
                                <?php
                                $q_area = mysqli_query($conn, "SELECT * FROM ongkir_area ORDER BY id_area ASC");
                                while ($area = mysqli_fetch_assoc($q_area)) {
                                    $selected = ($user['id_area'] == $area['id_area']) ? 'selected' : '';
                                    echo "<option value='{$area['id_area']}' $selected>{$area['nama_area']}</option>";
                                }
                                ?>
                            </select>
                            <div class="form-text mt-1"><i class="fas fa-truck me-1"></i> Pilih zona untuk menentukan ongkos kirim.</div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">Alamat Lengkap <span class="text-danger">*</span></label>
                            <textarea name="alamat" class="form-control" rows="4" placeholder="Tuliskan nama jalan, RT/RW, kelurahan, kecamatan, kota, dan kodepos dengan lengkap." required style="border-radius: 10px; resize: none;"><?= $user['alamat']; ?></textarea>
                            <div class="form-text mt-2"><i class="fas fa-info-circle me-1"></i> Pastikan alamat diisi selengkap mungkin untuk memudahkan kurir.</div>
                        </div>

                        <button type="submit" name="simpan_alamat" class="btn btn-primary w-100 py-3 fw-bold shadow-sm" style="border-radius: 12px; font-size: 16px;">
                            <i class="fas fa-save me-2"></i> SIMPAN ALAMAT
                        </button>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>