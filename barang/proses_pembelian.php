<?php

include 'koneksi.php';

$id_supplier = $_POST['id_supplier'];
$id_barang   = $_POST['id_barang'];
$jumlah      = $_POST['jumlah'];
$harga_beli  = $_POST['harga_beli'];

$subtotal = $jumlah * $harga_beli;

/* simpan pembelian */
$stmt = $conn->prepare("
INSERT INTO pembelian (tanggal, id_supplier, id_user, total_pembelian)
VALUES (NOW(), ?, 1, ?)
");

$stmt->bind_param("ii", $id_supplier, $subtotal);
$stmt->execute();
$id_pembelian = $conn->insert_id;

/* detail pembelian */
$stmt2 = $conn->prepare("
INSERT INTO detail_pembelian (id_pembelian, id_barang, jumlah, harga_beli, subtotal)
VALUES (?, ?, ?, ?, ?)
");

$stmt2->bind_param("iiiii", $id_pembelian, $id_barang, $jumlah, $harga_beli, $subtotal);
$stmt2->execute();

/* cek stok */
$cek = $conn->prepare("
SELECT jumlah_stok FROM stok WHERE id_barang = ?
");

$cek->bind_param("i", $id_barang);
$cek->execute();
$result = $cek->get_result();

if($result->num_rows > 0){
    $data = $result->fetch_assoc();
    $stok_baru = $data['jumlah_stok'] + $jumlah;

    $update = $conn->prepare("
    UPDATE stok SET
    jumlah_stok = ?
    WHERE id_barang = ?
    ");

    $update->bind_param("ii", $stok_baru, $id_barang);
    $update->execute();
    $update->close();
} else {
    
    $insert = $conn->prepare("
    INSERT INTO stok (id_barang, jumlah_stok, stok_minimum)
    VALUES (?, ?, 5)
    ");

    $insert->bind_param("ii", $id_barang, $jumlah);
    $insert->execute();
    $insert->close();
}

$stmt->close();
$stmt2->close();
$cek->close();
$conn->close();

header("Location: index.php");
exit();

// DIUBAH KE index.php
header("Location: index.php");
exit();

?>