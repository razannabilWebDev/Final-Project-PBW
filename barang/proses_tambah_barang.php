<?php


include 'koneksi.php';

$nama_barang   = $_POST['nama_barang'];
$kategori      = $_POST['kategori'];
$harga_beli    = $_POST['harga_beli'];
$harga_jual    = $_POST['harga_jual'];
$satuan        = $_POST['satuan'];
$status_barang = $_POST['status_barang'];
$jumlah_stok   = $_POST['jumlah_stok'];
$stok_minimum  = $_POST['stok_minimum'];

// Memulai transaksi database
$conn->begin_transaction();

try {
    // 1. Masukkan data ke tabel barang sesuai kolom skema database
    $stmt1 = $conn->prepare("
        INSERT INTO barang (nama_barang, kategori, harga_beli, harga_jual, satuan, tanggal_ditambahkan, status_barang)
        VALUES (?, ?, ?, ?, ?, NOW(), ?)
    ");
    $stmt1->bind_param("ssiiss", $nama_barang, $kategori, $harga_beli, $harga_jual, $satuan, $status_barang);
    $stmt1->execute();
    
    // Dapatkan ID Barang yang baru saja digenerate otomatis
    $id_barang_baru = $conn->insert_id;

    // 2. Masukkan data ke tabel stok 
    $stmt2 = $conn->prepare("
        INSERT INTO stok (id_barang, jumlah_stok, stok_minimum, terakhir_diupdate)
        VALUES (?, ?, ?, NOW())
    ");
    $stmt2->bind_param("iii", $id_barang_baru, $jumlah_stok, $stok_minimum);
    $stmt2->execute();

    // Jika kedua query berhasil tanpa interupsi, commit transaksi
    $conn->commit();

    $stmt1->close();
    $stmt2->close();

} catch (Exception $e) {
    // Jika ada kegagalan, batalkan semua manipulasi data
    $conn->rollback();
    die("Gagal menambahkan data barang dan stok: " . $e->getMessage());
}

$conn->close();

// Alihkan kembali ke halaman utama data barang
header("Location: index.php");
exit();