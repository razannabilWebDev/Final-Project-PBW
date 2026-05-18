<?php

include 'koneksi.php';

$stmt = $conn->prepare("
INSERT INTO barang
(nama_barang, kategori, stok, harga_beli, harga_jual, tanggal_masuk)
VALUES (?, ?, ?, ?, ?, ?)
");

$stmt->bind_param(
    "ssiiis",
    $_POST['nama_barang'],
    $_POST['kategori'],
    $_POST['stok'],
    $_POST['harga_beli'],
    $_POST['harga_jual'],
    $_POST['tanggal_masuk']
);

$stmt->execute();

$stmt->close();
$conn->close();

header("Location: index.php");

?>