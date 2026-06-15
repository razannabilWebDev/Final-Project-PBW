<?php
session_start();
include 'koneksi.php';

/* TOTAL BARANG */
$total_barang = $conn->query("
    SELECT COUNT(*) as total
    FROM barang
")->fetch_assoc()['total'];

/* STOK MENIPIS */
$stok_menipis = $conn->query("
    SELECT COUNT(*) as total
    FROM barang
    LEFT JOIN stok
        ON barang.id_barang = stok.id_barang
    WHERE IFNULL(stok.jumlah_stok,0)
          <= IFNULL(stok.stok_minimum,5)
")->fetch_assoc()['total'];

/* DAFTAR KATEGORI */
$daftar_kategori = $conn->query("
    SELECT DISTINCT kategori
    FROM barang
    WHERE kategori IS NOT NULL
    AND kategori <> ''
");

$search = $_GET['search'] ?? '';
$filter_kategori = $_GET['kategori'] ?? '';

$searchTerm = "%{$search}%";

$sql = "
    SELECT
        b.id_barang,
        b.nama_barang,
        b.kategori,
        b.harga_beli,
        b.harga_jual,
        b.satuan,
        b.tanggal_ditambahkan,
        b.status_barang,
        st.jumlah_stok,
        st.stok_minimum
    FROM barang b
    LEFT JOIN stok st
        ON b.id_barang = st.id_barang
    WHERE b.nama_barang LIKE ?
";

if (!empty($filter_kategori)) {
    $sql .= " AND b.kategori = ?";
}

$sql .= " ORDER BY b.id_barang ASC";

$stmt = $conn->prepare($sql);

if (!empty($filter_kategori)) {
    $stmt->bind_param(
        "ss",
        $searchTerm,
        $filter_kategori
    );
} else {
    $stmt->bind_param(
        "s",
        $searchTerm
    );
}

$stmt->execute();
$query = $stmt->get_result();
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
            <i class="bi bi-box-seam-fill"></i>
            Data Inventory
        </h2>

        <div class="d-flex gap-2">

    <a href="tambah_barang.php" class="btn btn-modern">
        <i class="bi bi-plus-circle-fill"></i>
        Tambah Barang
    </a>

   <a href="pembelian.php" class="btn btn-modern">
    <i class="bi bi-cart-plus-fill"></i>
    Pembelian
</a>

</div>

    </div>

    <form method="GET" class="mb-4">

        <div class="row g-2 align-items-center">

            <div class="col-md-5">
                <input
                    type="text"
                    name="search"
                    class="form-control"
                    placeholder="Cari Nama Barang..."
                    value="<?= htmlspecialchars($search); ?>">
            </div>

            <div class="col-md-5">

                <select name="kategori" class="form-select">

                    <option value="">
                        -- Semua Kategori --
                    </option>

                    <?php while($kat = $daftar_kategori->fetch_assoc()): ?>

                        <option
                            value="<?= htmlspecialchars($kat['kategori']); ?>"
                            <?= $filter_kategori == $kat['kategori'] ? 'selected' : ''; ?>>

                            <?= htmlspecialchars($kat['kategori']); ?>

                        </option>

                    <?php endwhile; ?>

                </select>

            </div>

            <div class="col-md-2">
                <button class="btn btn-modern w-100">
                    Cari
                </button>
            </div>

        </div>

    </form>

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
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

            <?php if($query->num_rows > 0): ?>

                <?php while($row = $query->fetch_assoc()): ?>

                    <tr>

                        <td><?= $row['id_barang']; ?></td>

                        <td><?= htmlspecialchars($row['nama_barang']); ?></td>

                        <td><?= htmlspecialchars($row['kategori']); ?></td>

                        <td>

                            <?= $row['jumlah_stok'] ?? 0; ?>

                            <?php if(($row['jumlah_stok'] ?? 0) <= ($row['stok_minimum'] ?? 5)): ?>

                                <span class="badge bg-warning text-dark ms-2">
                                    Menipis
                                </span>

                            <?php endif; ?>

                        </td>

                        <td>
                            Rp <?= number_format($row['harga_beli'],0,',','.'); ?>
                        </td>

                        <td>
                            Rp <?= number_format($row['harga_jual'],0,',','.'); ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($row['satuan']); ?>
                        </td>

                        <td>
                            <?= $row['tanggal_ditambahkan']; ?>
                        </td>

                        <td>

                            <?php if($row['status_barang'] == 'aktif'): ?>

                                <span class="badge bg-success">
                                    Aktif
                                </span>

                            <?php else: ?>

                                <span class="badge bg-danger">
                                    Nonaktif
                                </span>

                            <?php endif; ?>

                        </td>

                        <td>

                            <div class="d-flex gap-2">

                                <a href="edit_barang.php?id=<?= $row['id_barang']; ?>"
                                   class="btn btn-warning btn-sm text-white">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <a href="hapus_barang.php?id=<?= $row['id_barang']; ?>"
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Yakin hapus data?')">
                                    <i class="bi bi-trash-fill"></i>
                                </a>

                            </div>

                        </td>

                    </tr>

                <?php endwhile; ?>

            <?php else: ?>

                <tr>
                    <td colspan="10" class="text-center text-muted py-4">
                        Data barang tidak ditemukan.
                    </td>
                </tr>

            <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>
</div>

<?php
$stmt->close();
$conn->close();
?>
