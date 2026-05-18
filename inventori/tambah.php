<!DOCTYPE html>
<html>
<head>

<title>Tambah Barang</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<link rel="stylesheet" href="style.css">

</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="content">

<div class="card-custom">

<h2 class="title">

<i class="bi bi-plus-circle-fill"></i>
Tambah Barang

</h2>

<form action="proses_tambah.php" method="POST">

<div class="mb-3">

<label>Nama Barang</label>

<input type="text"
name="nama_barang"
class="form-control"
required>

</div>

<div class="mb-3">

<label>Kategori</label>

<input type="text"
name="kategori"
class="form-control"
required>

</div>

<div class="mb-3">

<label>Stok</label>

<input type="number"
name="stok"
class="form-control"
required>

</div>

<div class="mb-3">

<label>Harga Beli</label>

<input type="number"
name="harga_beli"
class="form-control"
required>

</div>

<div class="mb-3">

<label>Harga Jual</label>

<input type="number"
name="harga_jual"
class="form-control"
required>

</div>

<div class="mb-3">

<label>Tanggal Masuk</label>

<input type="date"
name="tanggal_masuk"
class="form-control"
required>

</div>

<button class="btn btn-modern">

<i class="bi bi-save-fill"></i>
Simpan

</button>

</form>

</div>

</div>

</body>
</html>