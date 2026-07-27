<?php
require 'config/koneksi.php';

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

$sql = "SELECT p.*, k.nama_kategori FROM produk p 
        LEFT JOIN kategori k ON p.id_kategori = k.id_kategori 
        $where_sql ORDER BY p.id_produk DESC";
$query = mysqli_query($conn, $sql);

if (mysqli_num_rows($query) > 0) {
    while ($p = mysqli_fetch_assoc($query)) {
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
<?php
    }
} else {
?>
    <div class="col-12 text-center py-5 my-5 animate__animated animate__fadeIn">
        <div class="bg-white p-5 rounded-4 shadow-sm mx-auto" style="max-width: 500px;">
            <img src="https://cdn-icons-png.flaticon.com/512/6134/6134065.png" width="100" class="mb-4 opacity-50">
            <h4 class="fw-bold text-dark">Tidak Ada Produk</h4>
            <p class="text-muted mb-0">Maaf Tuan, suku cadang yang dicari tidak ditemukan atau stok kosong.</p>
        </div>
    </div>
<?php
}
?>