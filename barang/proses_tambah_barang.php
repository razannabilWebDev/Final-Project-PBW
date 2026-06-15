<?php

include 'koneksi.php';

$nama_barang   = trim($_POST['nama_barang']);
$kategori      = trim($_POST['kategori']);
$harga_beli    = (int) $_POST['harga_beli'];
$harga_jual    = (int) $_POST['harga_jual'];
$satuan        = trim($_POST['satuan']);
$status_barang = trim($_POST['status_barang']);
$jumlah_stok   = (int) $_POST['jumlah_stok'];
$stok_minimum  = (int) $_POST['stok_minimum'];

$conn->begin_transaction();

try {

    $stmt1 = $conn->prepare("
        INSERT INTO barang
        (
            nama_barang,
            kategori,
            harga_beli,
            harga_jual,
            satuan,
            tanggal_ditambahkan,
            status_barang
        )
        VALUES
        (
            ?, ?, ?, ?, ?, NOW(), ?
        )
    ");

    $stmt1->bind_param(
        "ssiiss",
        $nama_barang,
        $kategori,
        $harga_beli,
        $harga_jual,
        $satuan,
        $status_barang
    );

    $stmt1->execute();

    $id_barang_baru = $conn->insert_id;

    $stmt2 = $conn->prepare("
        INSERT INTO stok
        (
            id_barang,
            jumlah_stok,
            stok_minimum,
            terakhir_diupdate
        )
        VALUES
        (
            ?, ?, ?, NOW()
        )
    ");

    $stmt2->bind_param(
        "iii",
        $id_barang_baru,
        $jumlah_stok,
        $stok_minimum
    );

    $stmt2->execute();

    $conn->commit();

    $stmt1->close();
    $stmt2->close();

} catch (Exception $e) {

    $conn->rollback();

    die("Gagal menambahkan data barang dan stok: " . $e->getMessage());
}

$conn->close();

header("Location: index.php");
exit();
?>