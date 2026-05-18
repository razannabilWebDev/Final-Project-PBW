<?php
// Menghubungkan file ini dengan file koneksi.php
// File koneksi.php berisi konfigurasi koneksi ke database MySQL
include 'koneksi.php';
?>

<!DOCTYPE html>
<html>
<head>
    <!-- Judul halamann -->
    <title>Inventori Barang</title>

    <!-- Menghubungkan Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Menghubungkan Bootstrap Icons utk icon -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php
// Menampilkan sidebar dari file sidebar.php
// Biasanya berisi menu navigasi
include 'sidebar.php';
?>

<!-- Konten utama halaman -->
<div class="content">

<?php
// ==========================================================
// MENGHITUNG TOTAL SEMUA BARANG
// ==========================================================

// Query untuk menghitung jumlah seluruh data pada tabel barang
$total = $conn->query("
    SELECT COUNT(*) as total
    FROM barang
")->fetch_assoc()['total'];


// ==========================================================
// MENGHITUNG JUMLAH BARANG DENGAN STOK MENIPIS (<= 5)
// ==========================================================

// Barang dengan stok kurang dari atau sama dengan 5 dianggap hampir habis
$stok_habis = $conn->query("
    SELECT COUNT(*) as habis
    FROM barang
    WHERE stok <= 5
")->fetch_assoc()['habis'];
?>

<!-- ======================================================
     KARTU STATISTIK
     Menampilkan Total Barang dan Stok Menipis
====================================================== -->
<div class="row mb-4">

    <!-- Kartu Total Barang -->
    <div class="col-md-6">
        <div class="stat-card">
            <h5>
                <i class="bi bi-box-seam-fill"></i>
                Total Barang
            </h5>

            <!-- Menampilkan jumlah total barang -->
            <h2><?= $total; ?></h2>
        </div>
    </div>

    <!-- Kartu Stok Menipis -->
    <div class="col-md-6">
        <div class="stat-card">
            <h5>
                <i class="bi bi-exclamation-triangle-fill"></i>
                Stok Menipis
            </h5>

            <!-- Menampilkan jumlah barang dengan stok <= 5 -->
            <h2><?= $stok_habis; ?></h2>
        </div>
    </div>

</div>

<!-- ======================================================
     KARTU UTAMA DATA INVENTORY
====================================================== -->
<div class="card-custom">

    <!-- Judul halaman dan tombol tambah barang -->
    <div class="d-flex justify-content-between align-items-center mb-4">

        <h2 class="title">
            <i class="bi bi-box2-heart-fill"></i>
            Data Inventory
        </h2>

        <!-- Tombol menuju halaman tambah.php -->
        <a href="tambah.php" class="btn btn-modern">
            <i class="bi bi-plus-circle-fill"></i>
            Tambah Barang
        </a>

    </div>

    <!-- ==================================================
         FORM PENCARIAN DAN FILTER
    =================================================== -->
    <form method="GET" class="row g-3 mb-4">

        <!-- Input pencarian berdasarkan nama barang -->
        <div class="col-md-5">
            <input type="text"
                   name="search"
                   class="form-control"
                   placeholder="Cari barang...">
        </div>

        <!-- Dropdown filter kategori -->
        <div class="col-md-4">
            <select name="kategori" class="form-select">
                <option value="">Semua Kategori</option>
                <option value="Makanan">Makanan</option>
                <option value="Minuman">Minuman</option>
                <option value="Sembako">Sembako</option>
            </select>
        </div>

        <!-- Tombol submit pencarian -->
        <div class="col-md-3">
            <button class="btn btn-modern w-100">
                <i class="bi bi-search"></i>
                Cari
            </button>
        </div>

    </form>

    <!-- ==================================================
         TABEL DATA BARANG
    =================================================== -->
    <table class="table align-middle">

        <!-- Header tabel -->
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

<?php
// ==========================================================
// MENGAMBIL DATA DARI URL (GET)
// ==========================================================

// Nilai search dari form pencarian
// Jika kosong, default-nya string kosong
$search = $_GET['search'] ?? '';

// Nilai kategori dari dropdown
// Jika kosong, default-nya string kosong
$kategori = $_GET['kategori'] ?? '';


// ==========================================================
// QUERY SQL DINAMIS
// ==========================================================

// cari berdasarkan nama barang
$query = "SELECT * FROM barang WHERE nama_barang LIKE ?";

// Jika kategori dipilih, tambahkan kondisi kategori
if ($kategori != '') {
    $query .= " AND kategori = ?";
}


// ==========================================================
// PREPARED STATEMENT
// ==========================================================

// Prepared statement digunakan untuk keamanan
// agar terhindar dari SQL Injection
$stmt = $conn->prepare($query);


// ==========================================================
// MENGISI PARAMETER QUERY
// ==========================================================

if ($kategori != '') {

    // Jika kategori dipilih:
    // parameter 1 = nama barang (LIKE)
    // parameter 2 = kategori
    $like = "%$search%";
    $stmt->bind_param("ss", $like, $kategori);

} else {

    // Jika kategori tidak dipilih:
    // hanya parameter nama barang
    $like = "%$search%";
    $stmt->bind_param("s", $like);

}


// ==========================================================
// MENJALANKAN QUERY
// ==========================================================

// Eksekusi query
$stmt->execute();

// Ambil hasil query
$result = $stmt->get_result();


// ==========================================================
// MENAMPILKAN DATA SATU PER SATU
// ==========================================================

// fetch_assoc() mengambil data dalam bentuk array asosiatif
while ($row = $result->fetch_assoc()) :
?>

            <tr>

                <!-- ID Barang -->
                <td><?= $row['id_barang']; ?></td>

                <!-- Nama Barang -->
                <td><?= $row['nama_barang']; ?></td>

                <!-- Kategori -->
                <td><?= $row['kategori']; ?></td>

                <!-- Stok -->
                <td>
                    <?= $row['stok']; ?>

                    <?php
                    // Jika stok kurang dari atau sama dengan 5
                    // tampilkan badge "Habis"
                    if ($row['stok'] <= 5) {
                        echo "<span class='badge-stock'>Habis</span>";
                    }
                    ?>
                </td>

                <!-- Harga Beli dengan format rupiah -->
                <td>
                    Rp <?= number_format($row['harga_beli']); ?>
                </td>

                <!-- Harga Jual dengan format rupiah -->
                <td>
                    Rp <?= number_format($row['harga_jual']); ?>
                </td>

                <!-- Tanggal Masuk -->
                <td><?= $row['tanggal_masuk']; ?></td>
                <td>

                    <!-- Tombol Edit -->
                    <a href="edit.php?id=<?= $row['id_barang']; ?>"
                       class="btn btn-action btn-edit">
                        <i class="bi bi-pencil-square"></i>
                    </a>

                    <!-- Tombol Hapus -->
                    <a href="hapus.php?id=<?= $row['id_barang']; ?>"
                       class="btn btn-action btn-delete"
                       onclick="return confirm('Yakin hapus?')">
                        <i class="bi bi-trash-fill"></i>
                    </a>

                </td>

            </tr>

<?php
// Akhir perulangan while
endwhile;
?>

        </tbody>
    </table>

</div> <!-- akhir card-custom -->
</div> <!-- akhir content -->

</body>
</html>

<?php
// ==========================================================
// MENUTUP PREPARED STATEMENT DAN KONEKSI DATABASE
// ==========================================================

// Membebaskan resource statement
$stmt->close();

// Menutup koneksi ke database
$conn->close();
?>