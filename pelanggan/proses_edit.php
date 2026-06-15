<?php
session_start();
require_once '../config/koneksi.php';
require_once '../config/session.php';
cek_login();


if (isset($_POST['simpan'])) {

    $id = $_POST['id_pelanggan'];
    $nama = $_POST['nama_pelanggan'];
    $alamat = $_POST['alamat'];
    $no_hp = $_POST['no_hp'];
    $poin = $_POST['poin_member'];

    $query = "UPDATE pelanggan SET nama_pelanggan='$nama', alamat='$alamat', no_hp='$no_hp', poin_member='$poin' WHERE id_pelanggan='$id'";
    $hasil = mysqli_query($conn, $query);

    if ($hasil) {
        echo "<script>alert('Data berhasil diubah!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Gagal mengubah data.'); window.location='edit.php?id_pelanggan=$id';</script>";
    }
}
?>