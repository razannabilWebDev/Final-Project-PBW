<?php
require '../config/koneksi.php';

$keyword = $_GET['keyword'] ?? '';

$query = mysqli_query($conn,"
    SELECT b.id_barang,
           b.nama_barang,
           b.harga_jual,
           s.jumlah_stok
    FROM barang b
    JOIN stok s ON b.id_barang = s.id_barang
    WHERE b.nama_barang LIKE '%$keyword%'
    AND s.jumlah_stok > 0
    LIMIT 10
");

while($row = mysqli_fetch_assoc($query)) :
?>

<option value="<?= $row['id_barang'] ?>">
    <?= $row['nama_barang'] ?>
    | Stok: <?= $row['jumlah_stok'] ?>
    | Rp<?= number_format($row['harga_jual']) ?>
</option>

<?php endwhile; ?>