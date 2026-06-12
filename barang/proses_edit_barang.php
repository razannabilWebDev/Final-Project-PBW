<?php

include 'koneksi.php';

$stmt = $conn->prepare("
UPDATE barang SET
nama_barang = ?,
kategori = ?,
harga_beli = ?,
harga_jual = ?,
satuan = ?,
status_barang = ?
WHERE id_barang = ?
");

$stmt->bind_param(
    "ssiissi",
    $_POST['nama_barang'],
    $_POST['kategori'],
    $_POST['harga_beli'],
    $_POST['harga_jual'],
    $_POST['satuan'],
    $_POST['status_barang'],
    $_POST['id_barang']
);

$stmt->execute();

$stmt->close();
$conn->close();

// DIUBAH KE index.php
header("Location: index.php");
exit();

?>