<?php
session_start();
require_once '../config/koneksi.php';

if (!isset($conn)) {
    die("Error: Variabel \$conn tidak ditemukan. Periksa file config/koneksi.php Tuan.");
}

if (!isset($_SESSION['id_pengguna']) || ($_SESSION['id_peran'] != 1 && $_SESSION['id_peran'] != 2)) {
    exit("Anda tidak memiliki akses untuk mencetak laporan.");
}

$tgl_awal = $_GET['tgl_awal'] ?? date('Y-m-01');
$tgl_akhir = $_GET['tgl_akhir'] ?? date('Y-m-t');
$jenis_laporan = $_GET['jenis_laporan'] ?? 'produk';

?>
<!DOCTYPE html>
<html>

<head>
    <title>Cetak Laporan - Damar Wulan AC & Radiator</title>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            padding: 30px;
            color: #000;
        }

        .kop {
            text-align: center;
            border-bottom: 3px double #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
            font-size: 12px;
        }

        th {
            background: #f2f2f2;
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .footer {
            margin-top: 50px;
            float: right;
            text-align: center;
            width: 250px;
        }
    </style>
</head>

<body onload="window.print()">
    <div class="kop">
        <h1 style="margin:0;">DAMAR WULAN AC & RADIATOR</h1>
        <p style="margin:5px 0;">Spesialis AC Mobil & Radiator | Jl. Bukit Indah No.1, Serua, Kec. Ciputat, Kota Tangerang Selatan, Banten 15414</p>
    </div>

    <?php if ($jenis_laporan == 'produk'): ?>
        <?php
        // JOIN 3 TABEL: transaksi, detail_transaksi, produk
        $sql = "SELECT t.tgl_transaksi, t.id_transaksi, p.nama_produk, d.jumlah, d.harga_satuan, (d.jumlah * d.harga_satuan) as subtotal
            FROM transaksi t 
            JOIN detail_transaksi d ON t.id_transaksi = d.id_transaksi 
            JOIN produk p ON d.id_produk = p.id_produk 
            WHERE t.status_transaksi = 'selesai' AND DATE(t.tgl_transaksi) BETWEEN '$tgl_awal' AND '$tgl_akhir'
            ORDER BY t.tgl_transaksi ASC";
        $res = mysqli_query($conn, $sql);
        $total_semua = 0;
        ?>
        <h3 style="text-align:center; text-decoration:underline;">LAPORAN RINCIAN PRODUK TERJUAL</h3>
        <p style="text-align:center;">Periode: <?= $tgl_awal; ?> s/d <?= $tgl_akhir; ?></p>
        <table>
            <thead>
                <tr>
                    <th>NO</th>
                    <th>TANGGAL</th>
                    <th>INVOICE</th>
                    <th>NAMA PRODUK</th>
                    <th>QTY</th>
                    <th>HARGA SATUAN</th>
                    <th>SUBTOTAL</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                while ($row = mysqli_fetch_assoc($res)): $total_semua += $row['subtotal']; ?>
                    <tr>
                        <td class="text-center"><?= $no++; ?></td>
                        <td><?= date('d/m/Y', strtotime($row['tgl_transaksi'])); ?></td>
                        <td><?= $row['id_transaksi']; ?></td>
                        <td><?= $row['nama_produk']; ?></td>
                        <td class="text-center"><?= $row['jumlah']; ?></td>
                        <td class="text-right">Rp <?= number_format($row['harga_satuan'], 0, ',', '.'); ?></td>
                        <td class="text-right">Rp <?= number_format($row['subtotal'], 0, ',', '.'); ?></td>
                    </tr>
                <?php endwhile; ?>
                <tr>
                    <th colspan="6" class="text-right">GRAND TOTAL</th>
                    <th class="text-right">Rp <?= number_format($total_semua, 0, ',', '.'); ?></th>
                </tr>
            </tbody>
        </table>

    <?php elseif ($jenis_laporan == 'area'): ?>
        <?php
        // JOIN 3 TABEL: transaksi, pengguna, ongkir_area
        $sql = "SELECT t.tgl_transaksi, t.id_transaksi, u.nama, o.nama_area, t.ongkir 
            FROM transaksi t 
            JOIN pengguna u ON t.id_pengguna = u.id_pengguna 
            JOIN ongkir_area o ON u.id_area = o.id_area 
            WHERE t.status_transaksi = 'selesai' AND DATE(t.tgl_transaksi) BETWEEN '$tgl_awal' AND '$tgl_akhir'
            ORDER BY o.nama_area ASC, t.tgl_transaksi ASC";
        $res = mysqli_query($conn, $sql);
        $total_ongkir = 0;
        ?>
        <h3 style="text-align:center; text-decoration:underline;">LAPORAN PENGIRIMAN BERDASARKAN AREA</h3>
        <p style="text-align:center;">Periode: <?= $tgl_awal; ?> s/d <?= $tgl_akhir; ?></p>
        <table>
            <thead>
                <tr>
                    <th>NO</th>
                    <th>TANGGAL</th>
                    <th>INVOICE</th>
                    <th>NAMA PELANGGAN</th>
                    <th>AREA PENGIRIMAN</th>
                    <th>ONGKOS KIRIM</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                while ($row = mysqli_fetch_assoc($res)): $total_ongkir += $row['ongkir']; ?>
                    <tr>
                        <td class="text-center"><?= $no++; ?></td>
                        <td><?= date('d/m/Y', strtotime($row['tgl_transaksi'])); ?></td>
                        <td><?= $row['id_transaksi']; ?></td>
                        <td><?= $row['nama']; ?></td>
                        <td><?= $row['nama_area']; ?></td>
                        <td class="text-right">Rp <?= number_format($row['ongkir'], 0, ',', '.'); ?></td>
                    </tr>
                <?php endwhile; ?>
                <tr>
                    <th colspan="5" class="text-right">TOTAL PENDAPATAN ONGKIR</th>
                    <th class="text-right">Rp <?= number_format($total_ongkir, 0, ',', '.'); ?></th>
                </tr>
            </tbody>
        </table>

    <?php elseif ($jenis_laporan == 'kategori'): ?>
        <?php
        // JOIN 4 TABEL: transaksi, detail_transaksi, produk, kategori
        $sql = "SELECT k.nama_kategori, p.nama_produk, SUM(d.jumlah) as total_qty, SUM(d.jumlah * d.harga_satuan) as total_rupiah 
            FROM transaksi t 
            JOIN detail_transaksi d ON t.id_transaksi = d.id_transaksi 
            JOIN produk p ON d.id_produk = p.id_produk 
            JOIN kategori k ON p.id_kategori = k.id_kategori 
            WHERE t.status_transaksi = 'selesai' AND DATE(t.tgl_transaksi) BETWEEN '$tgl_awal' AND '$tgl_akhir'
            GROUP BY k.id_kategori, p.id_produk 
            ORDER BY k.nama_kategori ASC, total_rupiah DESC";
        $res = mysqli_query($conn, $sql);
        $total_semua = 0;
        ?>
        <h3 style="text-align:center; text-decoration:underline;">LAPORAN KINERJA PENJUALAN PER KATEGORI</h3>
        <p style="text-align:center;">Periode: <?= $tgl_awal; ?> s/d <?= $tgl_akhir; ?></p>
        <table>
            <thead>
                <tr>
                    <th>NO</th>
                    <th>KATEGORI</th>
                    <th>NAMA PRODUK</th>
                    <th>TOTAL QTY TERJUAL</th>
                    <th>TOTAL PENDAPATAN</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                while ($row = mysqli_fetch_assoc($res)): $total_semua += $row['total_rupiah']; ?>
                    <tr>
                        <td class="text-center"><?= $no++; ?></td>
                        <td><?= $row['nama_kategori']; ?></td>
                        <td><?= $row['nama_produk']; ?></td>
                        <td class="text-center"><?= $row['total_qty']; ?></td>
                        <td class="text-right">Rp <?= number_format($row['total_rupiah'], 0, ',', '.'); ?></td>
                    </tr>
                <?php endwhile; ?>
                <tr>
                    <th colspan="4" class="text-right">GRAND TOTAL</th>
                    <th class="text-right">Rp <?= number_format($total_semua, 0, ',', '.'); ?></th>
                </tr>
            </tbody>
        </table>

    <?php elseif ($jenis_laporan == 'status_pesanan'): ?>
        <?php
        $sql = "SELECT t.id_transaksi, t.tgl_transaksi, u.nama, p.nama_produk, d.jumlah, d.harga_satuan, t.status_transaksi 
            FROM transaksi t 
            JOIN pengguna u ON t.id_pengguna = u.id_pengguna 
            JOIN detail_transaksi d ON t.id_transaksi = d.id_transaksi
            JOIN produk p ON d.id_produk = p.id_produk
            WHERE DATE(t.tgl_transaksi) BETWEEN '$tgl_awal' AND '$tgl_akhir'
            ORDER BY t.tgl_transaksi ASC, t.id_transaksi ASC";
        $res = mysqli_query($conn, $sql);
        ?>
        <h3 style="text-align:center; text-decoration:underline;">LAPORAN DETAIL STATUS PESANAN PELANGGAN</h3>
        <p style="text-align:center;">Periode: <?= $tgl_awal; ?> s/d <?= $tgl_akhir; ?></p>
        <table>
            <thead>
                <tr>
                    <th>NO</th>
                    <th>INVOICE</th>
                    <th>TANGGAL</th>
                    <th>NAMA PELANGGAN</th>
                    <th>NAMA PRODUK</th>
                    <th>QTY</th>
                    <th>HARGA SATUAN</th>
                    <th>STATUS</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                while ($row = mysqli_fetch_assoc($res)): ?>
                    <tr>
                        <td class="text-center"><?= $no++; ?></td>
                        <td><?= $row['id_transaksi']; ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($row['tgl_transaksi'])); ?></td>
                        <td><?= $row['nama']; ?></td>
                        <td><?= $row['nama_produk']; ?></td>
                        <td class="text-center"><?= $row['jumlah']; ?></td>
                        <td class="text-right">Rp <?= number_format($row['harga_satuan'], 0, ',', '.'); ?></td>
                        <td class="text-center" style="text-transform: uppercase;"><?= $row['status_transaksi']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <div class="footer">
        <p>Tangerang, <?= date('d F Y'); ?></p>
        <p>Owner,</p>
        <br><br><br>
        <p><strong>( M Akmal Fauzan )</strong></p>
    </div>
</body>

</html>