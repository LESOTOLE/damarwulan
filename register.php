<?php include 'includes/header.php'; ?>

<style>
    /* CSS LANGSUNG UNTUK REGISTER */
    .auth-wrapper {
        min-height: 100vh;
        display: flex;
        background-color: #fcfcfc;
    }

    .auth-side-info {
        /* Gradasi Merah ke Navy khas Logo Damar Wulan */
        background: linear-gradient(135deg, #d32f2f 0%, #1a237e 100%);
        color: white;
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 80px;
        position: relative;
    }

    /* Dekorasi Lingkaran */
    .auth-side-info::after {
        content: "";
        position: absolute;
        top: -50px;
        right: -50px;
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

    .card-register {
        border: none;
        background: transparent;
        width: 100%;
        max-width: 550px;
        /* Sedikit lebih lebar untuk form daftar */
    }

    .form-control {
        border-radius: 12px;
        padding: 12px 15px;
        border: none;
        background-color: #ffffff;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.03);
    }

    .btn-navy {
        background-color: #1a237e;
        color: white;
        border-radius: 15px;
        padding: 15px;
        font-weight: 700;
        transition: 0.3s;
        border: none;
    }

    .btn-navy:hover {
        background-color: #0d47a1;
        transform: translateY(-3px);
        color: white;
        box-shadow: 0 10px 20px rgba(26, 35, 126, 0.2);
    }

    .btn-outline-custom {
        border-radius: 15px;
        font-weight: 700;
        border: 1.5px solid #eee;
        color: #666;
        transition: 0.3s;
        background: #fff;
    }

    .btn-outline-custom:hover {
        background-color: #1a237e;
        border-color: #1a237e;
        color: #fff;
    }
</style>

<div class="auth-wrapper">
    <div class="container-fluid p-0">
        <div class="row g-0 h-100" style="min-height: 100vh;">

            <div class="col-lg-5 d-none d-lg-flex auth-side-info">
                <div class="mb-4">
                    <i class="fas fa-user-plus fa-4x mb-4 text-white opacity-75"></i>
                </div>
                <h1 class="fw-800 display-5 mb-3">Mari Bergabung</h1>
                <p class="fs-5 opacity-75 fw-500">
                    Dapatkan akses eksklusif untuk pemesanan suku cadang AC Mobil kualitas premium dari Damar Wulan AC.
                </p>
                <div class="mt-5">
                    <div class="mb-2"><i class="fas fa-check-circle me-2 text-white"></i> Harga Kompetitif</div>
                    <div class="mb-2"><i class="fas fa-check-circle me-2 text-white"></i> Stok Terlengkap</div>
                    <div class="mb-2"><i class="fas fa-check-circle me-2 text-white"></i> Pengiriman Cepat</div>
                </div>
            </div>

            <div class="col-lg-7 auth-form-side">
                <div class="card-register">
                    <div class="mb-4">
                        <h2 class="fw-800 text-dark mb-2">Buat Akun Baru</h2>
                        <p class="text-muted">Lengkapi data Tuan untuk mulai berbelanja di katalog kami.</p>
                    </div>

                    <form action="aksi_register.php" method="POST">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label small fw-bold">Nama Lengkap</label>
                                <input type="text" name="nama" class="form-control" placeholder="Contoh: Akmal Fauzan" required>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label small fw-bold">Alamat Email</label>
                                <input type="email" name="email" class="form-control" placeholder="nama@email.com" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Kata Sandi</label>
                                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Konfirmasi Sandi</label>
                                <input type="password" name="konfirmasi" class="form-control" placeholder="••••••••" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-navy w-100 shadow mt-4 mb-3">
                            Daftar Sekarang <i class="fas fa-paper-plane ms-2"></i>
                        </button>

                        <a href="login.php" class="btn btn-outline-custom w-100 py-3 mb-2 shadow-sm">
                            <i class="fas fa-sign-in-alt me-2"></i> Sudah Punya Akun? Masuk
                        </a>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>