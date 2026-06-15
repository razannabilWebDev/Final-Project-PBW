<?php
session_start();
require_once '../config/koneksi.php';
require_once '../config/session.php';
cek_login();


if (isset($_POST['simpan'])) {

    $nama = $_POST['nama_pelanggan'];
    $alamat = $_POST['alamat'];
    $no_hp = $_POST['no_hp'];
    $poin = $_POST['poin_member'];

    $query = "INSERT INTO pelanggan (nama_pelanggan, alamat, no_hp, poin_member) VALUES ('$nama', '$alamat', '$no_hp', '$poin')";
    $hasil = mysqli_query($conn, $query);

    if ($hasil) {
        echo "<script>alert('Data berhasil ditambahkan!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan data.'); window.location='tambah.php';</script>";
    }
}
?>