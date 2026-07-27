<?php
require 'config/koneksi.php';
$q = mysqli_query($conn, 'DESCRIBE transaksi');
if (!$q) {
    echo "Error: " . mysqli_error($conn) . "\n";
} else {
    while ($row = mysqli_fetch_assoc($q)) {
        echo $row['Field'] . "\n";
    }
}
?>
