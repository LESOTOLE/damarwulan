<?php
session_start();
require 'config/koneksi.php';

// --- LOGIKA ASLI TUAN (TIDAK DIUBAH) ---
$keyword = mysqli_real_escape_string($conn, $_GET['cari'] ?? '');
$kat_filter = mysqli_real_escape_string($conn, $_GET['kategori'] ?? '');
$where_clauses = [];
if (!empty($keyword)) {
    $where_clauses[] = "p.nama_produk LIKE '%$keyword%'";
}
if (!empty($kat_filter)) {
    $where_clauses[] = "p.id_kategori = '$kat_filter'";
}
$where_sql = count($where_clauses) > 0 ? "WHERE " . implode(" AND ", $where_clauses) : "";
$sql_produk = "SELECT p.*, k.nama_kategori FROM produk p LEFT JOIN kategori k ON p.id_kategori = k.id_kategori $where_sql ORDER BY p.id_produk DESC";
$query_produk = mysqli_query($conn, $sql_produk);

include 'includes/header.php';
include 'includes/navbar.php';
?>
<meta name="google-site-verification" content="d-2xkANSePIER695Jf7RqBKrn39Zqqr0SgL9Me8qVU0" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

<style>
    :root {
        --primary-navy: #0f172a;
        --accent-red: #ef4444;
        --dark-gradient: linear-gradient(135deg, #020617 0%, #0f172a 100%);
        --glass-bg: rgba(255, 255, 255, 0.85);
        --glass-border: rgba(255, 255, 255, 0.3);
    }

    body {
        background-color: #f4f7f9;
        position: relative;
    }

    /* Subtle background blobs */
    .bg-blob-1,
    .bg-blob-2 {
        position: absolute;
        border-radius: 50%;
        filter: blur(80px);
        z-index: -1;
        opacity: 0.6;
    }

    .bg-blob-1 {
        top: 400px;
        left: -100px;
        width: 400px;
        height: 400px;
        background: #e0e7ff;
    }

    .bg-blob-2 {
        top: 800px;
        right: -50px;
        width: 300px;
        height: 300px;
        background: #fee2e2;
    }

    /* Dark Mode Hero Section */
    .hero-section {
        background: var(--dark-gradient);
        position: relative;
        /* Dihapus overflow: hidden agar konten tidak terpotong */
        border-bottom: none;
        min-height: 400px;
    }

    /* Pseudo-element ::before dihapus untuk mencegah scrollbar tanpa overflow: hidden */

    .hero-content {
        position: relative;
        z-index: 1;
    }

    .hero-title {
        color: #ffffff !important;
        text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        font-size: 3.5rem;
    }

    .hero-subtitle {
        color: #cbd5e1;
    }

    /* Glassmorphism Search Bar */
    .search-wrapper {
        margin-top: -45px;
        position: relative;
        z-index: 10;
    }

    .search-container {
        background: var(--glass-bg);
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        border: 1px solid var(--glass-border) !important;
        border-radius: 50px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        padding: 8px;
        max-width: 100% !important;
        /* Override header.php */
        width: 100% !important;
    }

    .search-container:focus-within {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(15, 23, 42, 0.15) !important;
        background: #ffffff;
    }

    /* Horizontal Filter Pills */
    .filter-scroll {
        display: flex;
        overflow-x: auto;
        gap: 15px;
        padding-bottom: 10px;
        scrollbar-width: none;
    }

    .filter-scroll::-webkit-scrollbar {
        display: none;
    }

    .filter-pill {
        white-space: nowrap;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        color: #64748b;
        padding: 10px 24px;
        border-radius: 50px;
        font-weight: 600;
        transition: all 0.3s;
        cursor: pointer;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
    }

    .filter-pill:hover,
    .filter-pill.active {
        background: var(--primary-navy);
        color: #ffffff;
        border-color: var(--primary-navy);
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(15, 23, 42, 0.15);
    }

    /* Premium Product Cards */
    .product-card {
        background: #ffffff;
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border: 1px solid rgba(0, 0, 0, 0.04) !important;
    }

    .product-card:hover {
        transform: translateY(-12px);
        box-shadow: 0 25px 50px rgba(15, 23, 42, 0.12) !important;
    }

    .product-title-hover {
        transition: color 0.3s ease;
    }

    .product-title-hover:hover {
        color: var(--accent-red) !important;
    }

    .img-container {
        overflow: hidden;
        height: 220px;
        background: #f8fafc;
        position: relative;
    }

    .img-container img {
        transition: transform 0.6s ease;
    }

    .product-card:hover .img-container img {
        transform: scale(1.08);
    }

    .stock-indicator {
        position: absolute;
        bottom: 15px;
        left: 15px;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(5px);
        padding: 6px 14px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 800;
        color: #10b981;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .stock-indicator.low {
        color: #ef4444;
    }

    .btn-add-cart {
        background: var(--primary-navy);
        color: #fff;
        width: 48px;
        height: 48px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s;
        border: none;
    }

    .btn-add-cart:hover {
        background: var(--accent-red) !important;
        transform: rotate(90deg) scale(1.1);
        box-shadow: 0 10px 25px rgba(239, 68, 68, 0.4);
    }
</style>

<!-- Dekorasi Background -->
<div class="bg-blob-1"></div>
<div class="bg-blob-2"></div>

<section class="hero-section text-center animate__animated animate__fadeIn" style="height: auto !important; padding: 100px 0 80px 0 !important;">
    <div class="container hero-content">
        <h1 class="hero-title fw-800 mb-3 animate__animated animate__slideInDown">
            Find the Best Parts <br>
            <span style="color: var(--accent-red);">for Your Vehicle.</span>
        </h1>
        <p class="hero-subtitle fs-5 mb-5">Katalog suku cadang AC dan Radiator original standar Singapore & Japan.</p>

        <div class="row justify-content-center mt-5">
            <div class="col-lg-8">
                <form action="index.php" method="GET" id="searchForm" class="search-container w-100 mx-auto d-flex align-items-center shadow-lg animate__animated animate__zoomIn" style="border-radius: 50px;">
                    <div class="ps-4 pe-2">
                        <i class="fas fa-search fs-5 text-muted"></i>
                    </div>
                    <input type="text" id="inputCari" name="cari" class="form-control border-0 shadow-none fs-5 bg-transparent" placeholder="Cari sparepart (misal: Avanza, Blower)..." value="<?= $keyword; ?>" autocomplete="off">
                    <button type="submit" class="btn btn-primary px-5 py-3 fw-bold shadow-sm" style="border-radius: 40px;">Cari</button>
                </form>
            </div>
        </div>
    </div>
</section>

<div class="container mt-5 pb-5">
    <!-- Horizontal Pills Filter -->
    <div class="row mb-4 animate__animated animate__fadeInUp">
        <div class="col-12">
            <div class="d-flex align-items-center mb-2">
                <h6 class="fw-800 mb-0 me-3" style="letter-spacing: 1px;"><i class="fas fa-sliders-h me-2 text-danger"></i> KATEGORI</h6>
                <div class="flex-grow-1" style="height: 1px; background: #e2e8f0;"></div>
            </div>
            <div class="filter-scroll py-2">
                <label class="filter-pill <?= empty($kat_filter) ? 'active' : '' ?>">
                    <input type="radio" name="kategori" value="" class="d-none category-radio" <?= empty($kat_filter) ? 'checked' : '' ?>>
                    <i class="fas fa-th-large me-2"></i>Semua Kategori
                </label>
                <?php
                $q_kat = mysqli_query($conn, "SELECT * FROM kategori");
                while ($k = mysqli_fetch_assoc($q_kat)) {
                    $sel = ($kat_filter == $k['id_kategori']) ? 'active' : '';
                    $chk = ($kat_filter == $k['id_kategori']) ? 'checked' : '';
                    echo "<label class='filter-pill $sel'>";
                    echo "<input type='radio' name='kategori' value='" . $k['id_kategori'] . "' class='d-none category-radio' $chk>";
                    echo "<i class='fas fa-cube me-2 opacity-75'></i>" . $k['nama_kategori'];
                    echo "</label>";
                }
                ?>
            </div>
        </div>
    </div>

    <!-- Product Grid -->
    <div class="row">
        <div class="col-12">
            <div id="tampilProduk" class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">
                <?php
                if (mysqli_num_rows($query_produk) > 0):
                    while ($p = mysqli_fetch_assoc($query_produk)):
                        $stock_class = ($p['stok'] < 5) ? 'low' : '';
                        $stock_icon = ($p['stok'] < 5) ? 'fa-exclamation-circle' : 'fa-check-circle';
                ?>
                        <div class="col animate__animated animate__fadeInUp">
                            <div class="card product-card h-100 shadow-sm p-3">
                                <div class="img-container mb-4">
                                    <?php $img_src = (!empty($p['gambar'])) ? "assets/img/" . $p['gambar'] : "assets/img/default-part.jpg"; ?>
                                    <a href="detail_produk.php?id=<?= $p['id_produk'] ?>">
                                        <img src="<?= $img_src; ?>" onerror="this.src='assets/img/default-part.jpg';"
                                            class="w-100 h-100" style="object-fit: cover;">
                                    </a>
                                    <div class="stock-indicator <?= $stock_class ?>">
                                        <i class="fas <?= $stock_icon ?>"></i> Sisa <?= $p['stok']; ?>
                                    </div>
                                </div>
                                <div class="card-body pt-0 d-flex flex-column px-2">
                                    <div class="mb-2">
                                        <span class="badge" style="background: #fff5f5; color: var(--accent-red); padding: 6px 12px; border-radius: 8px; font-size: 11px; font-weight: 700; letter-spacing: 0.5px;">
                                            <?= strtoupper($p['nama_kategori'] ?? 'SPAREPART'); ?>
                                        </span>
                                    </div>
                                    <a href="detail_produk.php?id=<?= $p['id_produk'] ?>" class="text-decoration-none">
                                        <h5 class="fw-800 text-dark mb-3 product-title-hover" style="font-size: 16px; line-height: 1.4;"><?= $p['nama_produk']; ?></h5>
                                    </a>

                                    <div class="mt-auto d-flex justify-content-between align-items-end">
                                        <div class="price-box">
                                            <small class="text-muted fw-bold mb-1 d-block" style="font-size: 11px; letter-spacing: 0.5px;">HARGA</small>
                                            <h5 class="fw-900 text-danger mb-0">Rp <?= number_format($p['harga'], 0, ',', '.'); ?></h5>
                                        </div>
                                        <button class="btn-add-cart btn-add-ajax shadow-sm"
                                            data-id="<?= $p['id_produk']; ?>">
                                            <i class="fas fa-plus fs-5"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile;
                else: ?>
                    <div class="col-12 text-center py-5 my-5 animate__animated animate__fadeIn">
                        <div class="bg-white p-5 rounded-4 shadow-sm mx-auto" style="max-width: 500px;">
                            <img src="https://cdn-icons-png.flaticon.com/512/6134/6134065.png" width="100" class="mb-4 opacity-50">
                            <h4 class="fw-bold text-dark">Tidak Ada Produk</h4>
                            <p class="text-muted mb-0">Maaf Tuan, suku cadang yang dicari tidak ditemukan atau stok kosong.</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // 1. LOGIKA ANIMASI TERBANG (Gunakan delegasi agar tombol hasil search tetap bisa diklik)
    $(document).on('click', '.btn-add-ajax', function(e) {
        e.preventDefault();

        let id_produk = $(this).data('id');
        let cartBtn = $('#cart-icon');
        let productImg = $(this).closest('.product-card').find('img');

        // Pastikan Gambar dan Icon Keranjang ditemukan
        if (productImg.length > 0 && cartBtn.length > 0) {

            // Buat Clone Gambar untuk terbang
            let imgClone = productImg.clone()
                .offset({
                    top: productImg.offset().top,
                    left: productImg.offset().left
                })
                .css({
                    'opacity': '0.9',
                    'position': 'absolute',
                    'height': productImg.height() + 'px',
                    'width': productImg.width() + 'px',
                    'object-fit': 'cover',
                    'z-index': '9999',
                    'border-radius': '15px',
                    'pointer-events': 'none',
                    'box-shadow': '0 10px 30px rgba(0,0,0,0.2)'
                })
                .appendTo($('body'));

            // Animasi terbang ke keranjang
            imgClone.animate({
                'top': cartBtn.offset().top,
                'left': cartBtn.offset().left,
                'width': '20px',
                'height': '20px',
                'opacity': 0.1
            }, 800, 'swing', function() {
                // Setelah selesai terbang, hapus clone
                $(this).remove();
                
                // Efek Getar (Tada) pada Icon Keranjang
                cartBtn.removeClass('animate__animated animate__tada'); 
                setTimeout(() => cartBtn.addClass('animate__animated animate__tada'), 10);
                setTimeout(() => cartBtn.removeClass('animate__animated animate__tada'), 1000);
            });

            // Jalankan AJAX di background
            $.ajax({
                url: "aksi_keranjang.php?id=" + id_produk + "&ajax=1",
                method: "GET",
                dataType: "json",
                success: function(response) {
                    // Update Angka di Badge Keranjang
                    $('#cart-count').text(response.total_item);
                },
                error: function() {
                    imgClone.remove();
                    Swal.fire('Gagal', 'Gagal menambah barang ke keranjang.', 'error');
                }
            });
        } else {
            // Fallback jika animasi gagal (misal selector salah), langsung redirect saja
            window.location.href = "aksi_keranjang.php?id=" + id_produk;
        }
    });

    // --- JS LOGIKA PENCARIAN & FILTER ---
    $(document).ready(function() {
        let debounceTimer;

        function load_data(keyword = '', kategori = '') {
            $.ajax({
                url: "get_produk.php",
                method: "GET",
                data: {
                    cari: keyword,
                    kategori: kategori
                },
                beforeSend: function() {
                    $('#tampilProduk').html('<div class="col-12 text-center py-5"><div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status"><span class="visually-hidden">Loading...</span></div><p class="mt-3 text-muted fw-bold">Sedang memuat katalog...</p></div>');
                },
                success: function(data) {
                    $('#tampilProduk').html(data);
                    // Re-trigger animasi untuk elemen baru
                    $('.product-card').closest('.col').addClass('animate__animated animate__fadeInUp');
                }
            });
        }

        $('#inputCari').on('keyup', function() {
            clearTimeout(debounceTimer);
            let keyword = $(this).val();
            let selectedKategori = $('.category-radio:checked').val() || '';
            
            debounceTimer = setTimeout(function() {
                load_data(keyword, selectedKategori);
            }, 400); // delay 400ms
        });

        $('.category-radio').on('change', function() {
            // Update UI Pill Active
            $('.filter-pill').removeClass('active');
            $(this).closest('.filter-pill').addClass('active');

            // Panggil Ajax
            let keyword = $('#inputCari').val();
            load_data(keyword, $(this).val());
        });

        // Mencegah form pencarian reload halaman (full ajax)
        $('#searchForm').on('submit', function(e) {
            e.preventDefault();
            let selectedKategori = $('.category-radio:checked').val() || '';
            load_data($('#inputCari').val(), selectedKategori);
        });
    });
</script>
<?php include 'includes/footer.php'; ?>
