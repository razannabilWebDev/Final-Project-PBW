<?php
session_start();

// 1. Proteksi Halaman & Validasi Role sesuai Template
if (!isset($_SESSION['role'])) {
    header("Location: ../login.php"); 
    exit;
}

$role_sekarang = $_SESSION['role']; 

// Menggunakan berkas koneksi bawaan Data Inventory
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

// Sanitasi Input Menggunakan MySQLi Escape String bawaan objek $conn
$search_escaped = $conn->real_escape_string($search);
$searchTerm = "%{$search_escaped}%";

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
    $stmt->bind_param("ss", $searchTerm, $filter_kategori);
} else {
    $stmt->bind_param("s", $searchTerm);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Inventory</title>

    <!-- Menyerasikan CSS dengan Template Utama -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>

<body>
    <div class="wrapper d-flex max-vh-100">
        
        <!-- SIDEBAR COMPONENT -->
        <div>
            <?php include '../templates/sidebar.php'; ?>
        </div>
        
        <!-- MAIN CONTENT AREA -->
        <div class="main-content">
            <div class="content-body">
                <div class="mb-4">
                    <h2 class="fw-bold mb-1">Data Inventory</h2>
                    <p class="text-muted mb-3">Kawasan kelola inventory barang</p>
                    
                    <!-- STATISTIK CARDS (Disesuaikan agar seimbang dengan layout template) -->
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <div class="card border-0 rounded-4 shadow-sm p-4 bg-light">
                                <h6 class="text-muted mb-1 fw-bold">Total Barang</h6>
                                <h2 class="fw-bold m-0 text-primary"><?= $total_barang; ?></h2>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card border-0 rounded-4 shadow-sm p-4 bg-light">
                                <h6 class="text-muted mb-1 fw-bold">Stok Menipis</h6>
                                <h2 class="fw-bold m-0 text-danger"><?= $stok_menipis; ?></h2>
                            </div>
                        </div>
                    </div>

                    <!-- FILTER & SEARCH FORM BAR -->
                    <div class="card-body p-4.5 mb-4">
                        <div class="d-flex align-items-center justify-content-between gap-4 w-100 flex-wrap">
                            
                            <form action="" method="GET" class="flex-grow-1" style="max-width: 65%;">
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <input type="text" name="search" class="form-control" placeholder="Cari Nama Barang..." 
                                               value="<?= htmlspecialchars($search); ?>" 
                                               style="border: 1px solid #7a7a7a; height: 48px; border-radius: 8px;">
                                    </div>
                                    <div class="col-md-6 position-relative">
                                        <select name="kategori" class="form-select" style="border: 1px solid #7a7a7a; height: 48px; border-radius: 8px; padding-right: 45px;">
                                            <option value="">-- Semua Kategori --</option>
                                            <?php while($kat = $daftar_kategori->fetch_assoc()): ?>
                                                <option value="<?= htmlspecialchars($kat['kategori']); ?>" <?= $filter_kategori == $kat['kategori'] ? 'selected' : ''; ?>>
                                                    <?= htmlspecialchars($kat['kategori']); ?>
                                                </option>
                                            <?php endwhile; ?>
                                        </select>
                                        <button type="submit" class="btn p-0 position-absolute end-0 top-50 translate-middle-y me-4 text-secondary border-0 bg-transparent">
                                            <i class="fa-solid fa-magnifying-glass fs-5"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>

                            <!-- TOMBOL AKSI UTAMA -->
                            <div class="d-flex gap-2">
                                <a href="../dashboard/admin.php" class="btn btn-outline-secondary rounded-4 px-3">
                                    <i class="fas fa-arrow-left me-2"></i>Kembali
                                </a>
                                <a href="tambah_barang.php" class="btn btn-primary rounded-4">
                                    <i class="fas fa-plus me-2"></i>Tambah Barang
                                </a>
                                <a href="pembelian.php" class="btn btn-success rounded-4">
                                    <i class="fas fa-cart-plus me-2"></i>Pembelian
                                </a>
                            </div>

                        </div>
                    </div>
                        
                    <!-- DATA TABLE CARD -->
                    <div class="card border-0 rounded-4 shadow-sm">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table align-middle">
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
                                        <?php if($result->num_rows > 0): ?>
                                            <?php while($row = $result->fetch_assoc()): ?>
                                            <tr>
                                                <td><?= $row['id_barang']; ?></td>
                                                <td><?= htmlspecialchars($row['nama_barang']); ?></td>
                                                <td><?= htmlspecialchars($row['kategori']); ?></td>
                                                <td>
                                                    <?= $row['jumlah_stok'] ?? 0; ?>
                                                    <?php if(($row['jumlah_stok'] ?? 0) <= ($row['stok_minimum'] ?? 5)): ?>
                                                        <span class="badge bg-warning text-dark ms-2">Menipis</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>Rp <?= number_format($row['harga_beli'], 0, ',', '.'); ?></td>
                                                <td>Rp <?= number_format($row['harga_jual'], 0, ',', '.'); ?></td>
                                                <td><?= htmlspecialchars($row['satuan']); ?></td>
                                                <td><?= $row['tanggal_ditambahkan']; ?></td>
                                                <td>
                                                    <?php if($row['status_barang'] == 'aktif'): ?>
                                                        <span class="badge bg-success">Aktif</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-danger">Nonaktif</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-3">
                                                        <!-- Tombol Edit -->
                                                        <a href="edit_barang.php?id=<?= $row['id_barang']; ?>" 
                                                           class="text-dark fs-5 link-underline link-underline-opacity-0" 
                                                           title="Edit Data">
                                                            <i class="fa-regular fa-pen-to-square"></i>
                                                        </a>
                                
                                                        <!-- Proteksi Tombol Hapus Hanya Untuk Admin -->
                                                        <?php if ($role_sekarang === 'admin') { ?>
                                                            <a href="hapus_barang.php?id=<?= $row['id_barang']; ?>" 
                                                               onclick="return confirm('Ingin menghapus data ini?')" 
                                                               class="text-dark fs-5 link-underline link-underline-opacity-0" 
                                                               title="Hapus Data">
                                                                <i class="fa-solid fa-trash-can"></i>
                                                            </a>
                                                        <?php } ?>
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

                </div>
            </div> 
        </div> 
    </div> 
    <!-- FOOTER COMPONENT -->
            <?php include '../templates/footer.php'; ?>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>