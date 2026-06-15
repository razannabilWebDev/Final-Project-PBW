<?php
session_start();
require '../config/koneksi.php';

$index = $_POST['index'];
$qty = $_POST['qty'];

$id_barang = $_SESSION['keranjang'][$index]['id_barang'];

$query = mysqli_query($conn,"
    SELECT s.jumlah_stok
    FROM stok s
    WHERE s.id_barang='$id_barang'
");

$data = mysqli_fetch_assoc($query);

if($qty > $data['jumlah_stok']){

    echo "
    <script>
        alert('Stok tidak mencukupi');
        window.location='index.php';
    </script>";
    exit;
}

$_SESSION['keranjang'][$index]['qty'] = $qty;

$_SESSION['keranjang'][$index]['subtotal'] =
    $_SESSION['keranjang'][$index]['harga'] * $qty;

header('Location:index.php');