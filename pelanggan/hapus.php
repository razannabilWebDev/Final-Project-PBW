<?php
session_start();

// Proteksi Ganda: Jika bukan admin, tendang keluar / tolak proses hapus
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('Akses ditolak! Kasir tidak memiliki izin untuk menghapus data.'); window.location='index.php';</script>";
    exit;
}

$koneksi = mysqli_connect("localhost", "root", "", "warung_kelontong");

// Mengambil ID dari URL
$id = $_GET['id_pelanggan'];

// Jalankan query hapus data
$query = "DELETE FROM pelanggan WHERE id_pelanggan = '$id'";
$hasil = mysqli_query($koneksi, $query);

if ($hasil) {
    echo "<script>alert('Data sukses dihapus!'); window.location='index.php';</script>";
} else {
    echo "<script>alert('Gagal menghapus data.'); window.location='index.php';</script>";
}
?>