<?php
session_start();
require_once '../config/koneksi.php';
require_once '../config/session.php';
cek_login();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('Akses ditolak! Kasir tidak memiliki izin untuk menghapus data.'); window.location='index.php';</script>";
    exit;
}


$id = $_GET['id_pelanggan'];

$query = "DELETE FROM pelanggan WHERE id_pelanggan = '$id'";
$hasil = mysqli_query($conn, $query);

if ($hasil) {
    echo "<script>alert('Data sukses dihapus!'); window.location='index.php';</script>";
} else {
    echo "<script>alert('Gagal menghapus data.'); window.location='index.php';</script>";
}
?>