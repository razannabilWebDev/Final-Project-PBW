<?php
session_start();
if (!isset($_SESSION['role'])) {
    header("Location: ../login.php");
    exit;
}

if (isset($_POST['simpan'])) {
    $koneksi = mysqli_connect("localhost", "root", "", "warung_kelontong");

    $id = $_POST['id_pelanggan'];
    $nama = $_POST['nama_pelanggan'];
    $alamat = $_POST['alamat'];
    $no_hp = $_POST['no_hp'];
    $poin = $_POST['poin_member'];

    $query = "UPDATE pelanggan SET nama_pelanggan='$nama', alamat='$alamat', no_hp='$no_hp', poin_member='$poin' WHERE id_pelanggan='$id'";
    $hasil = mysqli_query($koneksi, $query);

    if ($hasil) {
        echo "<script>alert('Data berhasil diubah!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Gagal mengubah data.'); window.location='edit.php?id_pelanggan=$id';</script>";
    }
}
?>