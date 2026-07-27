<?php
// Cek halaman saat ini untuk logika sembunyikan elemen
$current_page = basename($_SERVER['PHP_SELF']);
?>

<style>
    /* Navbar Transparan & Blur (Kekinian) */
    .navbar-modern {
        background: rgba(255, 255, 255, 0.98);
        /* Warnanya dibuat lebih solid sedikit */
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border-bottom: none;
        /* Kita hapus garis bawahnya */
        box-shadow: 0 4px 25px rgba(0, 0, 0, 0.08);
        /* INI KUNCINYA: Shadow lembut di bawah navbar */
        padding: 15px 0;
        transition: all 0.3s ease;
    }

    /* Styling Brand / Logo */
    .navbar-brand img {
        width: 70px;
        /* Logo diperbesar */
        height: 70px;
    }

    .navbar-brand .brand-text {
        font-weight: 800;
        font-size: 26px;
        /* Teks logo diperbesar */
        letter-spacing: -0.5px;
        color: #1a237e;
    }

    /* Menu Item */
    .nav-link-modern {
        font-weight: 600;
        font-size: 16px;
        /* Font menu diperbesar */
        color: #64748b !important;
        padding: 10px 20px !important;
        border-radius: 10px;
        transition: 0.3s;
    }

    .nav-link-modern:hover {
        background: #f1f5f9;
        color: #1a237e !important;
    }

    /* Dropdown Profile yang Mewah */
    .profile-dropdown .dropdown-menu {
        border: none;
        border-radius: 15px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        padding: 15px;
        min-width: 220px;
        margin-top: 25px;
    }

    .dropdown-item-modern {
        padding: 10px 15px;
        border-radius: 8px;
        font-weight: 500;
        color: #475569;
        transition: 0.2s;
    }

    .dropdown-item-modern:hover {
        background: #f8fafc;
        color: #1a237e;
        transform: translateX(5px);
    }
</style>

<nav class="navbar navbar-expand-lg navbar-light sticky-top navbar-modern">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="index.php">
            <img src="./logo.png"  class="me-2" alt="Logo">
            <span class="brand-text">DAMAR<span class="text-primary">WULAN</span></span>
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                <li class="nav-item">
                    <a class="nav-link nav-link-modern" href="index.php">Katalog</a>
                </li>
            </ul>

            <div class="d-flex align-items-center">

                <a href="keranjang.php" id="cart-icon" class="me-4 position-relative text-dark text-decoration-none">
                    <i class="fas fa-shopping-basket" style="font-size: 26px;"></i> <span id="cart-count" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 12px; padding: 5px 8px;">
                        <?= isset($_SESSION['keranjang']) ? count($_SESSION['keranjang']) : 0; ?>
                    </span>
                </a>

                <?php if (isset($_SESSION['id_pengguna'])) : ?>
                    <div class="dropdown profile-dropdown">
                        <a class="d-flex align-items-center text-decoration-none" href="#" role="button" data-bs-toggle="dropdown">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 48px; height: 48px; font-weight: 700; font-size: 20px;">
                                <?= isset($_SESSION['nama']) ? substr($_SESSION['nama'], 0, 1) : 'U'; ?>
                            </div>
                            <div class="ms-2 d-none d-lg-block">
                                <small class="text-muted d-block" style="font-size: 13px;">Halo,</small>
                                <span class="fw-bold text-navy" style="font-size: 16px;"><?= isset($_SESSION['nama']) ? $_SESSION['nama'] : 'User'; ?></span>
                            </div>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end animate__animated animate__fadeIn">
                            <li>
                                <h6 class="dropdown-header">Akun Saya</h6>
                            </li>
                            <li><a class="dropdown-item dropdown-item-modern" href="./alamat.php"><i class="fas fa-map-marker-alt me-2"></i> Alamat Saya</a></li>
                            <li><a class="dropdown-item dropdown-item-modern" href="./riwayat.php"><i class="fas fa-history me-2"></i> Riwayat Pesanan</a></li>
                            <li><a class="dropdown-item dropdown-item-modern" href="./ubah_password.php"><i class="fas fa-key me-2"></i> Ganti Password</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item dropdown-item-modern text-danger" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i> Keluar</a></li>
                        </ul>
                    </div>
                <?php else : ?>
                    <a href="login.php" class="btn btn-primary px-4 py-2 ms-3" style="border-radius: 10px; font-weight: 600;">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>