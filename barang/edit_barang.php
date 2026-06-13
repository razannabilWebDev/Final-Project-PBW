<?php

include 'koneksi.php';

$id = $_GET['id'];

$stmt = $conn->prepare("
SELECT * FROM barang
WHERE id_barang = ?
");

$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="style.css">

<div class="content">
    <div class="card-custom">
        <h2 class="title mb-4">
            <i class="bi bi-pencil-square"></i> Edit Barang
        </h2>

        <form action="proses_edit_barang.php" method="POST">
            <input type="hidden" name="id_barang" value="<?= $data['id_barang']; ?>">

            <div class="mb-3">
                <label>Nama Barang</label>
                <input type="text" name="nama_barang" class="form-control" value="<?= $data['nama_barang']; ?>" required>
            </div>

            <div class="mb-3">
                <label>Kategori</label>
                <input type="text" name="kategori" class="form-control" value="<?= $data['kategori']; ?>" required>
            </div>

            <div class="mb-3">
                <label>Harga Beli</label>
                <input type="number" name="harga_beli" class="form-control" value="<?= $data['harga_beli']; ?>" required>
            </div>

            <div class="mb-3">
                <label>Harga Jual</label>
                <input type="number" name="harga_jual" class="form-control" value="<?= $data['harga_jual']; ?>" required>
            </div>

            <div class="mb-3">
                <label>Satuan</label>
                <input type="text" name="satuan" class="form-control" value="<?= $data['satuan']; ?>" required>
            </div>

            <div class="mb-3">
                <label>Status Barang</label>
                <select name="status_barang" class="form-select">
                    <option value="aktif" <?= $data['status_barang'] == 'aktif' ? 'selected' : ''; ?>>Aktif</option>
                    <option value="nonaktif" <?= $data['status_barang'] == 'nonaktif' ? 'selected' : ''; ?>>Nonaktif</option>
                </select>
            </div>

            <button class="btn btn-modern">
                <i class="bi bi-check-circle-fill"></i> Update Barang
            </button>
        </form>
    </div>
</div>

<?php
$stmt->close();
$conn->close();
?>