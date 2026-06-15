<?php

include 'koneksi.php';

$barang = $conn->query("
SELECT * FROM barang
WHERE status_barang = 'aktif'
");

$supplier = $conn->query("
SELECT * FROM supplier
");

?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<link rel="stylesheet" href="style.css">

<div class="content">

<div class="card-custom">

<h2 class="title mb-4">

<i class="bi bi-cart-fill"></i>
Pembelian Barang

</h2>

<form action="proses_pembelian.php" method="POST">

<div class="mb-3">

<label>Supplier</label>

<select name="id_supplier" class="form-select">

<?php while($s = $supplier->fetch_assoc()) : ?>

<option value="<?= $s['id_supplier']; ?>">

<?= $s['nama_supplier']; ?>

</option>

<?php endwhile; ?>

</select>

</div>

<div class="mb-3">

<label>Barang</label>

<select name="id_barang" class="form-select">

<?php while($b = $barang->fetch_assoc()) : ?>

<option value="<?= $b['id_barang']; ?>">

<?= $b['nama_barang']; ?>

</option>

<?php endwhile; ?>

</select>

</div>

<div class="mb-3">

<label>Jumlah</label>

<input type="number"
name="jumlah"
class="form-control"
required>

</div>

<div class="mb-3">

<label>Harga Beli</label>

<input type="number"
name="harga_beli"
class="form-control"
required>

<div class="d-flex justify-content-end gap-2 mt-4">

    <a href="index.php" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left-circle"></i>
        Batal
    </a>

    <button type="submit" class="btn btn-modern">
        <i class="bi bi-save-fill"></i>
        Simpan Pembelian
    </button>

</div>

</div>