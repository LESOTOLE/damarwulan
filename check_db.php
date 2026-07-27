<?php
require 'config/koneksi.php';
$result = mysqli_query($conn, "DESCRIBE transaksi");
while ($row = mysqli_fetch_assoc($result)) {
    echo $row['Field'] . " - " . $row['Type'] . "\n";
}
