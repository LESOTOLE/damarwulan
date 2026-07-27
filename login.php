<?php
session_start();
if (isset($_SESSION['id_pengguna'])) {
    header("Location: " . ($_SESSION['id_peran'] == 1 ? "admin/index.php" : "index.php"));
    exit();
}
include 'includes/header.php';
?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    .auth-wrapper {
        min-height: 100vh;
        display: flex;
        background-color: #fcfcfc;
    }

    .auth-side-info {
        background: linear-gradient(135deg, #1a237e 0%, #0d47a1 100%);
        color: white;
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 80px;
        position: relative;
        overflow: hidden;
    }

    .auth-side-info::after {
        content: "";
        position: absolute;
        bottom: -50px;
        left: -50px;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 50%;
    }

    .auth-form-side {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px;
    }

    .card-login {
        width: 100%;
        max-width: 420px;
    }

    .btn-navy {
        background: #1a237e;
        color: #fff;
        border-radius: 12px;
        padding: 15px;
        font-weight: 700;
        border: none;
        transition: 0.3s;
    }

    .btn-navy:hover {
        background: #0d47a1;
        transform: translateY(-3px);
        color: #fff;
        box-shadow: 0 10px 20px rgba(26, 35, 126, 0.2);
    }

    .form-control {
        border-radius: 12px;
        padding: 12px 15px;
        border: 1.5px solid #eee;
        background: #fff;
        transition: 0.3s;
    }

    .form-control:focus {
        border-color: #1a237e;
        box-shadow: none;
        background: #fff;
    }
</style>

<div class="auth-wrapper">
    <div class="container-fluid p-0">
        <div class="row g-0 h-100" style="min-height: 100vh;">
            <div class="col-lg-6 d-none d-lg-flex auth-side-info">
                <div class="mb-4"><img src="logo.png" style="width: 10%" alt=""></i></div>
                <h1 class="fw-800 display-4 mb-3">Damar Wulan AC</h1>
                <p class="fs-5 opacity-75">Solusi Suku Cadang AC Mobil & Radiator Terbaik. Kualitas Singapore & Japan di Tangan Tuan.</p>
                <div class="mt-5 pt-4 border-top border-white border-opacity-10">
                    <small class="opacity-50">© 2026 Damar Wulan AC Store.</small>
                </div>
            </div>

            <div class="col-lg-6 auth-form-side">
                <div class="card-login">
                    <div class="mb-5">
                        <h2 class="fw-800 text-dark mb-2">Selamat Datang</h2>
                        <p class="text-muted">Masuk ke akun Tuan untuk melanjutkan.</p>
                    </div>

                    <form action="aksi_login.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="nama@email.com" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label small fw-bold">Kata Sandi</label>
                            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                        </div>

                        <button type="submit" class="btn btn-navy w-100 shadow mb-4">
                            Masuk Sekarang <i class="fas fa-arrow-right ms-2"></i>
                        </button>

                        <div class="text-center">
                            <span class="text-muted small">Belum punya akun?</span>
                            <a href="register.php" class="text-decoration-none fw-bold ms-1" style="color: #d32f2f;">Daftar</a>
                            <hr class="my-4 opacity-10">
                            <a href="index.php" class="text-decoration-none text-muted small fw-bold">
                                <i class="fas fa-store me-1"></i> Kembali ke Katalog
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if (isset($_GET['status'])): ?>
    <script>
        const status = "<?= $_GET['status']; ?>";
        if (status === "gagal") {
            Swal.fire({
                icon: 'error',
                title: 'Login Gagal',
                text: 'Email atau Password salah, Tuan!',
                confirmButtonColor: '#1a237e'
            });
        } else if (status === "logout") {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil Keluar',
                text: 'Sesi Tuan telah berakhir.',
                confirmButtonColor: '#1a237e'
            });
        } else if (status === "belum_login") {
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian!',
                text: 'Silahkan login terlebih dahulu untuk melanjutkan pembayaran.',
                confirmButtonColor: '#1a237e'
            });
        }

        if (status === 'registrasi_sukses') {
            Swal.fire({
                icon: 'success',
                title: 'Pendaftaran Berhasil!',
                text: 'Silakan login dengan akun baru Tuan.',
                confirmButtonColor: '#1a237e'
            });
        } else if (status === 'gagal') {
            Swal.fire({
                icon: 'error',
                title: 'Login Gagal',
                text: 'Email atau Password salah, mohon cek kembali.',
                confirmButtonColor: '#d32f2f'
            });
        }
    </script>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>