<?php
session_start();
require '../config/koneksi.php';
// Proteksi: Hanya Role 1 (Owner) dan 2 (Admin)
if (!isset($_SESSION['id_pengguna']) || ($_SESSION['id_peran'] != 1 && $_SESSION['id_peran'] != 2)) {
    header("Location: ../login.php");
    exit();
}

include 'header_admin.php';
?>

<div class="container-fluid">
    <div class="row justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="col-md-5">
            <div class="card border-0 shadow-lg" style="border-radius: 25px; overflow: hidden;">
                <div class="p-5 text-center text-white" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
                    <div class="mb-3">
                        <i class="fas fa-shield-alt fa-3x text-danger"></i>
                    </div>
                    <h4 class="fw-800 m-0">Keamanan Akun</h4>
                    <p class="small opacity-50 m-0">Perbarui kata sandi Tuan secara berkala.</p>
                </div>

                <div class="card-body p-5 bg-white">
                    <form action="aksi_password.php" method="POST" id="formUpdatePassword">
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-muted text-uppercase" style="letter-spacing: 1px;">Kata Sandi Baru</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="fas fa-lock text-muted"></i></span>
                                <input type="password" name="pass_baru" id="pass_baru" class="form-control bg-light border-0 p-3" style="border-radius: 0 12px 12px 0;" placeholder="Masukkan password baru" required>
                            </div>
                        </div>

                        <div class="mb-5">
                            <label class="form-label small fw-bold text-muted text-uppercase" style="letter-spacing: 1px;">Konfirmasi Kata Sandi</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="fas fa-check-double text-muted"></i></span>
                                <input type="password" name="konfirmasi" id="konfirmasi" class="form-control bg-light border-0 p-3" style="border-radius: 0 12px 12px 0;" placeholder="Ulangi password baru" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-navy w-100 py-3 shadow-sm fw-bold">
                            SIMPAN PERUBAHAN <i class="fas fa-save ms-2"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Validasi Frontend sebelum kirim ke Backend
    document.getElementById('formUpdatePassword').onsubmit = function(e) {
        const pass1 = document.getElementById('pass_baru').value;
        const pass2 = document.getElementById('konfirmasi').value;

        if (pass1.length < 6) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Password Terlalu Pendek',
                text: 'Minimal gunakan 6 karakter ya Tuan!',
                confirmButtonColor: '#0f172a'
            });
            return;
        }

        if (pass1 !== pass2) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Tidak Cocok!',
                text: 'Konfirmasi password harus sama dengan password baru.',
                confirmButtonColor: '#d32f2f'
            });
        }
    };

    // Menangkap Notifikasi dari URL Parameter
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('status') === 'sukses') {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Password Tuan telah diperbarui.',
            showConfirmButton: false,
            timer: 2500
        });
    } else if (urlParams.get('status') === 'error') {
        Swal.fire({
            icon: 'error',
            title: 'Terjadi Kesalahan',
            text: 'Gagal memperbarui data, silakan coba lagi.',
            confirmButtonColor: '#0f172a'
        });
    }
</script>