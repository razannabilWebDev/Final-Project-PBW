<?php

session_start();

include '../templates/navbar.php';
include '../templates/sidebar.php';
include 'koneksi.php';

?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="style.css">

<div class="content p-4">
    <div class="card-custom">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <h2 class="title">
                <i class="bi bi-box-seam-fill"></i> Data Barang
            </h2>
            <div class="d-flex gap-2">
                <a href="tambah_barang.php" class="btn btn-modern">
                    <i class="bi bi-plus-circle-fill"></i> Tambah Barang
                </a>
                <a href="pembelian.php" class="btn btn-dark">
                    <i class="bi bi-cart-fill"></i> Pembelian
                </a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th>Stok</th>
                        <th>Harga Beli</th>
                        <th>Harga Jual</th>
                        <th>Satuan</th>
                        <th>Status</th>
                        <th width="180">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = $conn->query("
                        SELECT
                            barang.id_barang,
                            barang.nama_barang,
                            barang.kategori,
                            barang.harga_beli,
                            barang.harga_jual,
                            barang.satuan,
                            barang.status_barang,
                            stok.jumlah_stok
                        FROM barang
                        LEFT JOIN stok ON barang.id_barang = stok.id_barang
                        ORDER BY barang.id_barang DESC
                    ");

                    while($row = $query->fetch_assoc()) :
                    ?>
                    <tr>
                        <td><?= $row['id_barang']; ?></td>
                        <td><?= $row['nama_barang']; ?></td>
                        <td><?= $row['kategori']; ?></td>
                        <td>
                            <?= $row['jumlah_stok'] ?? 0; ?>
                            <?php if(($row['jumlah_stok'] ?? 0) <= 5) : ?>
                                <span class="badge bg-warning text-dark ms-2">Menipis</span>
                            <?php endif; ?>
                        </td>
                        <td>Rp <?= number_format($row['harga_beli']); ?></td>
                        <td>Rp <?= number_format($row['harga_jual']); ?></td>
                        <td><?= $row['satuan']; ?></td>
                        <td>
                            <?php if($row['status_barang'] == 'aktif') : ?>
                                <span class="badge bg-success">Aktif</span>
                            <?php else : ?>
                                <span class="badge bg-danger">Nonaktif</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="edit_barang.php?id=<?= $row['id_barang']; ?>" class="btn btn-warning btn-sm text-white">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>
                                <a href="hapus_barang.php?id=<?= $row['id_barang']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus data?')">
                                    <i class="bi bi-trash-fill"></i> Hapus
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
$conn->close();
include '../templates/footer.php';
?>