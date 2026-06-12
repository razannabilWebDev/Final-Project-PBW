<?php
session_start();
include 'koneksi.php';

/* TOTAL BARANG */
$total_barang = $conn->query("SELECT COUNT(*) as total FROM barang")->fetch_assoc()['total'];

/* STOK MENIPIS  */
$stok_menipis = $conn->query("
    SELECT COUNT(*) as total 
    FROM barang 
    LEFT JOIN stok ON barang.id_barang = stok.id_barang 
    WHERE IFNULL(stok.jumlah_stok, 0) <= IFNULL(stok.stok_minimum, 5)
")->fetch_assoc()['total'];

/* AMBIL DAFTAR KATEGORI UNTUK DROPDOWN FILTER */
$daftar_kategori = $conn->query("SELECT DISTINCT kategori FROM barang WHERE kategori IS NOT NULL AND kategori != ''");

/* SEARCH & FILTER KATEGORI */
$search = $_GET['search'] ?? '';
$filter_kategori = $_GET['kategori'] ?? '';

$sql = "SELECT
            barang.id_barang,
            barang.nama_barang,
            barang.kategori,
            barang.harga_beli,
            barang.harga_jual,
            barang.satuan,
            barang.tanggal_ditambahkan,
            barang.status_barang,
            stok.jumlah_stok
        FROM barang
        LEFT JOIN stok ON barang.id_barang = stok.id_barang
        WHERE barang.nama_barang LIKE '%$search%'";

if (!empty($filter_kategori)) {
    $sql .= " AND barang.kategori = '$filter_kategori'";
}

$sql .= " ORDER BY barang.id_barang ASC";
$query = $conn->query($sql);
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="style.css">

<div class="content">

    <div class="row mb-4">
        <div class="col-md-6 mb-3">
            <div class="stat-card">
                <h6>Total Barang</h6>
                <h2><?= $total_barang; ?></h2>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="stat-card">
                <h6>Stok Menipis</h6>
                <h2><?= $stok_menipis; ?></h2>
            </div>
        </div>
    </div>

    <div class="card-custom">
        <div class="mb-3">

    <a href="../dashboard/admin.php" class="btn btn-back">

        <i class="bi bi-arrow-left"></i>
        Kembali

    </a>

</div>
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <h2 class="title">
                <i class="bi bi-box-seam-fill"></i> Data Inventory
            </h2>
            <div class="d-flex gap-2">
                <a href="tambah_barang.php" class="btn btn-modern">
                    <i class="bi bi-plus-circle-fill"></i> Tambah Barang
                </a>
            </div>
        </div>

        <form method="GET" class="mb-4">
            <div class="row g-2 align-items-center">
                <div class="col-md-5">
                    <input type="text" name="search" class="form-control" placeholder="Cari Nama Barang..." value="<?= htmlspecialchars($search); ?>">
                </div>
                <div class="col-md-5">
                    <select name="kategori" class="form-select">
                        <option value="">-- Semua Kategori --</option>
                        <?php while($kat = $daftar_kategori->fetch_assoc()): ?>
                            <option value="<?= $kat['kategori']; ?>" <?= $filter_kategori == $kat['kategori'] ? 'selected' : ''; ?>>
                                <?= $kat['kategori']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-modern w-100">Cari</button>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table tabil-hover align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Stok</th>
                        <th>Harga Beli</th>
                        <th>Harga Jual</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($query->num_rows > 0): ?>
                        <?php while($row = $query->fetch_assoc()) : ?>
                        <tr>
                            <td><?= $row['id_barang']; ?></td>
                            <td><?= $row['nama_barang']; ?></td>
                            <td><?= $row['kategori']; ?></td>
                            <td>
                                <?= $row['jumlah_stok'] ?? 0; ?>
                                <?php if(($row['jumlah_stok'] ?? 0) <= 5): ?>
                                    <span class="badge bg-warning text-dark ms-2">Menipis</span>
                                <?php endif; ?>
                            </td>
                            <td>Rp <?= number_format($row['harga_beli']); ?></td>
                            <td>Rp <?= number_format($row['harga_jual']); ?></td>
                            <td><?= $row['tanggal_ditambahkan']; ?></td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="edit_barang.php?id=<?= $row['id_barang']; ?>" class="btn btn-warning btn-sm text-white">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <a href="hapus_barang.php?id=<?= $row['id_barang']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus data?')">
                                        <i class="bi bi-trash-fill"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">Data barang tidak ditemukan.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
$conn->close();
?>