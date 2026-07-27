<?php
session_start();
require 'config/koneksi.php';

// Validasi ID Produk
$id_produk = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Query Data Produk
$query = mysqli_query($conn, "
    SELECT p.*, k.nama_kategori 
    FROM produk p 
    LEFT JOIN kategori k ON p.id_kategori = k.id_kategori 
    WHERE p.id_produk = '$id_produk'
");

if (mysqli_num_rows($query) == 0) {
    echo "<script>alert('Produk tidak ditemukan!'); window.location='index.php';</script>";
    exit();
}

$p = mysqli_fetch_assoc($query);

// Menyiapkan gambar
$img_src = (!empty($p['gambar'])) ? "assets/img/" . $p['gambar'] : "assets/img/default-part.jpg";
$stock_class = ($p['stok'] < 5) ? 'text-danger' : 'text-success';
$stock_icon = ($p['stok'] < 5) ? 'fa-exclamation-circle' : 'fa-check-circle';

include 'includes/header.php';
include 'includes/navbar.php';
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

<style>
    :root {
        --primary-navy: #0f172a;
        --accent-red: #ef4444;
        --dark-gradient: linear-gradient(135deg, #020617 0%, #0f172a 100%);
    }

    body {
        background-color: #f4f7f9;
    }

    .detail-card {
        background: #ffffff;
        border-radius: 20px;
        overflow: hidden;
        border: 1px solid rgba(0, 0, 0, 0.04);
        box-shadow: 0 10px 40px rgba(15, 23, 42, 0.08);
    }

    .img-showcase {
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        border-radius: 20px;
        height: 100%;
        min-height: 400px;
    }

    .img-showcase img {
        max-width: 100%;
        max-height: 450px;
        object-fit: contain;
        border-radius: 10px;
        transition: transform 0.3s ease;
    }

    .img-showcase img:hover {
        transform: scale(1.05);
    }

    .badge-category {
        background: #fff5f5;
        color: var(--accent-red);
        padding: 8px 16px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 700;
        letter-spacing: 1px;
    }

    .spec-table th {
        width: 35%;
        color: #64748b;
        font-weight: 600;
        border-bottom: 1px dashed #e2e8f0;
        padding: 12px 0;
    }

    .spec-table td {
        font-weight: 700;
        color: var(--primary-navy);
        border-bottom: 1px dashed #e2e8f0;
        padding: 12px 0;
    }

    .btn-buy-now {
        background: var(--primary-navy);
        color: #fff;
        border-radius: 15px;
        padding: 15px 30px;
        font-weight: 700;
        font-size: 16px;
        transition: all 0.3s;
        border: none;
        width: 100%;
    }

    .btn-buy-now:hover {
        background: var(--accent-red);
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(239, 68, 68, 0.4);
        color: white;
    }
</style>

<div class="container py-5">
    
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4 animate__animated animate__fadeIn">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none text-muted"><i class="fas fa-home"></i> Katalog</a></li>
            <li class="breadcrumb-item"><a href="index.php?kategori=<?= $p['id_kategori'] ?>" class="text-decoration-none text-muted"><?= $p['nama_kategori'] ?? 'Kategori' ?></a></li>
            <li class="breadcrumb-item active fw-bold text-navy" aria-current="page"><?= $p['nama_produk'] ?></li>
        </ol>
    </nav>

    <div class="card detail-card animate__animated animate__fadeInUp">
        <div class="row g-0">
            <!-- Kolom Gambar -->
            <div class="col-lg-5 p-4">
                <div class="img-showcase">
                    <img src="<?= $img_src ?>" onerror="this.src='assets/img/default-part.jpg';" alt="<?= $p['nama_produk'] ?>" id="product-img">
                </div>
            </div>

            <!-- Kolom Detail -->
            <div class="col-lg-7 p-4 p-md-5">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <span class="badge-category text-uppercase"><?= $p['nama_kategori'] ?? 'SPAREPART' ?></span>
                    <span class="<?= $stock_class ?> fw-bold px-3 py-1 bg-light rounded-pill" style="font-size: 14px;">
                        <i class="fas <?= $stock_icon ?> me-1"></i> Sisa Stok: <?= $p['stok'] ?>
                    </span>
                </div>

                <h2 class="fw-900 text-navy mb-2" style="font-size: 2.2rem;"><?= $p['nama_produk'] ?></h2>
                <h3 class="fw-900 text-danger mb-4" style="font-size: 1.8rem;">Rp <?= number_format($p['harga'], 0, ',', '.') ?></h3>

                <p class="text-muted mb-4" style="line-height: 1.8;">
                    <?= !empty($p['deskripsi']) ? nl2br($p['deskripsi']) : 'Tidak ada deskripsi detail untuk produk ini.' ?>
                </p>

                <h6 class="fw-bold mb-3"><i class="fas fa-cog text-muted me-2"></i> Spesifikasi Produk</h6>
                <table class="table table-borderless spec-table mb-5">
                    <tbody>
                        <tr>
                            <th>Nomor Suku Cadang</th>
                            <td><?= !empty($p['nomor_suku_cadang']) ? $p['nomor_suku_cadang'] : '-' ?></td>
                        </tr>
                        <tr>
                            <th>Merk</th>
                            <td><?= !empty($p['merk']) ? $p['merk'] : 'Original / OEM' ?></td>
                        </tr>
                        <tr>
                            <th>Berat Fisik</th>
                            <td><?= ($p['berat_gram'] > 0) ? number_format($p['berat_gram']/1000, 2, ',', '.') . ' Kg' : '-' ?></td>
                        </tr>
                    </tbody>
                </table>

                <?php if ($p['stok'] > 0): ?>
                    <form action="aksi_keranjang.php" method="GET" class="d-flex align-items-center gap-3">
                        <input type="hidden" name="id" value="<?= $p['id_produk'] ?>">
                        <button type="submit" class="btn btn-buy-now">
                            <i class="fas fa-shopping-cart me-2"></i> Tambahkan ke Keranjang
                        </button>
                    </form>
                <?php else: ?>
                    <button class="btn btn-secondary w-100 py-3" style="border-radius: 15px; font-weight: 700;" disabled>
                        <i class="fas fa-ban me-2"></i> Stok Habis
                    </button>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
