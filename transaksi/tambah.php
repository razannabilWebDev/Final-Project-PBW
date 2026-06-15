<?php
require '../config/koneksi.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Transaksi</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">

<h2>Tambah Transaksi</h2>

<form action="proses/tambah.php" method="POST">

    <div class="mb-3">

        <label>Pelanggan</label>

        <select name="id_pelanggan" class="form-control">

            <?php

            $pelanggan = mysqli_query($conn,"SELECT * FROM pelanggan");

            while($p = mysqli_fetch_array($pelanggan)){

            ?>

            <option value="<?= $p['id_pelanggan']; ?>">
                <?= $p['nama_pelanggan']; ?>
            </option>

            <?php } ?>

        </select>

    </div>

    <div class="mb-3">

        <label>Barang</label>

        <select name="id_barang" class="form-control">

            <?php

            $barang = mysqli_query($conn,"SELECT * FROM barang");

            while($b = mysqli_fetch_array($barang)){

            ?>

            <option value="<?= $b['id_barang']; ?>">
                <?= $b['nama_barang']; ?>
            </option>

            <?php } ?>

        </select>

    </div>

    <div class="mb-3">

        <label>Jumlah</label>

        <input type="number" name="jumlah" class="form-control">

    </div>

    <button type="submit" class="btn btn-primary">
        Simpan
    </button>

</form>

</div>

</body>
</html>