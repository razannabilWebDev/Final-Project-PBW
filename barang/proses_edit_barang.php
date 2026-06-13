<?php

include 'koneksi.php';

$id_barang     = (int) $_POST['id_barang'];
$nama_barang   = trim($_POST['nama_barang']);
$kategori      = trim($_POST['kategori']);
$harga_beli    = (int) $_POST['harga_beli'];
$harga_jual    = (int) $_POST['harga_jual'];
$satuan        = trim($_POST['satuan']);
$status_barang = trim($_POST['status_barang']);

$stmt = $conn->prepare("
    UPDATE barang
    SET
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
    $nama_barang,
    $kategori,
    $harga_beli,
    $harga_jual,
    $satuan,
    $status_barang,
    $id_barang
);

if ($stmt->execute()) {

    $stmt->close();
    $conn->close();

    header("Location: index.php?success=update");
    exit();

} else {

    echo "Gagal mengupdate data barang: " . $stmt->error;

    $stmt->close();
    $conn->close();
}
?>