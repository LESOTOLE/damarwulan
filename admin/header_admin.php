<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Damar Wulan AutoParts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --sidebar-width: 280px;
            --deep-navy: #0f172a;
            --vibrant-red: #d32f2f;
            --light-bg: #f8fafc;
            --text-gray: #94a3b8;
        }

        body {
            background-color: var(--light-bg);
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
            color: #334155;
        }

        .wrapper {
            display: flex;
            width: 100%;
            align-items: stretch;
        }

        #sidebar {
            min-width: var(--sidebar-width);
            max-width: var(--sidebar-width);
            background: var(--deep-navy);
            color: #fff;
            min-height: 100vh;
            position: fixed;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1000;
            box-shadow: 4px 0 24px rgba(0, 0, 0, 0.15);
        }

        #sidebar .sidebar-header {
            padding: 30px 25px;
            background: rgba(255, 255, 255, 0.03);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .menu-label {
            padding: 25px 25px 10px;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1.8px;
            color: var(--text-gray);
            font-weight: 700;
            opacity: 0.6;
        }

        #sidebar ul.components {
            padding: 10px 0;
        }

        #sidebar ul li a {
            padding: 14px 25px;
            display: flex;
            align-items: center;
            color: rgba(255, 255, 255, 0.65);
            text-decoration: none;
            transition: 0.3s;
            font-size: 14.5px;
            font-weight: 500;
        }

        #sidebar ul li a i {
            margin-right: 15px;
            width: 20px;
            text-align: center;
            font-size: 18px;
        }

        #sidebar ul li a:hover,
        #sidebar ul li.active>a {
            color: #fff;
            background: rgba(211, 47, 47, 0.08);
            border-left: 5px solid var(--vibrant-red);
        }

        #sidebar ul li.active>a {
            background: rgba(211, 47, 47, 0.15);
            font-weight: 600;
        }

        #content {
            width: 100%;
            padding: 40px;
            margin-left: var(--sidebar-width);
            transition: all 0.4s;
        }

        .user-profile-info {
            padding: 25px;
            margin-bottom: 5px;
            background: rgba(255, 255, 255, 0.02);
        }

        .user-profile-info .label-login {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: rgba(255, 255, 255, 0.4);
            display: block;
            margin-bottom: 4px;
        }

        .user-profile-info .user-name {
            font-size: 15px;
            font-weight: 700;
            color: #ffffff;
            display: block;
        }

        .badge-role {
            padding: 4px 10px;
            font-size: 9px;
            font-weight: 800;
            letter-spacing: 0.5px;
            border-radius: 6px;
            margin-top: 8px;
            display: inline-block;
        }

        @media (max-width: 992px) {
            #sidebar {
                margin-left: calc(-1 * var(--sidebar-width));
            }

            #content {
                margin-left: 0;
                padding: 25px;
            }

            #sidebar.active {
                margin-left: 0;
            }
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <nav id="sidebar">
            <div class="sidebar-header">
                <h4 class="fw-bold m-0 text-white d-flex align-items-center">
                <img src="../logo.png" alt="Logo" class="me-2" style="height: 50px; width: auto;">
                Damarwulan
            </h4>
            </div>

            <div class="user-profile-info">
                <span class="label-login">Login sebagai:</span>
                <span class="user-name"><?= $_SESSION['nama']; ?></span>
                <span class="badge-role <?= ($_SESSION['id_peran'] == 1) ? 'bg-danger' : 'bg-success'; ?>">
                    <i class="fas <?= ($_SESSION['id_peran'] == 1) ? 'fa-crown' : 'fa-user-shield'; ?> me-1"></i>
                    <?= ($_SESSION['id_peran'] == 1) ? 'OWNER' : 'ADMIN STAFF'; ?>
                </span>
            </div>

            <ul class="list-unstyled components">
                <div class="menu-label">Menu Utama</div>

                <li class="<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
                    <a href="index.php"><i class="fas fa-chart-line"></i> Dashboard</a>
                </li>
                <li class="<?= basename($_SERVER['PHP_SELF']) == 'kategori.php' ? 'active' : ''; ?>">
                    <a href="kategori.php"><i class="fas fa-tags"></i> Kategori Produk</a>
                </li>
                <li class="<?= basename($_SERVER['PHP_SELF']) == 'produk.php' ? 'active' : ''; ?>">
                    <a href="produk.php"><i class="fas fa-boxes"></i> Stok Produk</a>
                </li>
                <li class="<?= basename($_SERVER['PHP_SELF']) == 'pesanan.php' ? 'active' : ''; ?>">
                    <a href="pesanan.php"><i class="fas fa-shopping-cart"></i> Pesanan</a>
                </li>
                <li class="<?= basename($_SERVER['PHP_SELF']) == 'ongkir.php' ? 'active' : ''; ?>">
                    <a href="ongkir.php"><i class="fas fa-map-marked-alt"></i> Ongkir Area</a>
                </li>

                <li class="<?= basename($_SERVER['PHP_SELF']) == 'laporan.php' ? 'active' : ''; ?>">
                    <a href="laporan.php"><i class="fas fa-file-invoice-dollar"></i> Laporan Laba</a>
                </li>

                <?php if ($_SESSION['id_peran'] == 1) : ?>
                    <div class="menu-label text-danger">Owner Only</div>
                    <li class="<?= basename($_SERVER['PHP_SELF']) == 'pengguna.php' ? 'active' : ''; ?>">
                        <a href="pengguna.php"><i class="fas fa-users-cog"></i> Kelola User</a>
                    </li>
                <?php endif; ?>

                <div class="menu-label">Pengaturan</div>
                <li class="<?= basename($_SERVER['PHP_SELF']) == 'ubah_password.php' ? 'active' : ''; ?>">
                    <a href="ubah_password.php"><i class="fas fa-key"></i> Ubah Password</a>
                </li>
                <li>
                    <a href="../logout.php" class="text-danger"><i class="fas fa-power-off"></i> Keluar</a>
                </li>
            </ul>
        </nav>

        <div id="content">