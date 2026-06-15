<?php
session_start();

// 1. Proteksi Halaman & Validasi Role sesuai Template Utama
if (!isset($_SESSION['role'])) {
    header("Location: ../login.php"); 
    exit;
}

$role_sekarang = $_SESSION['role']; 

// Mengambil file koneksi bawaan
include 'koneksi.php';

// Menangkap ID Barang dari URL
$id_barang = $_GET['id'] ?? '';

if (empty($id_barang)) {
    echo "<script>alert('ID Barang tidak ditemukan!'); window.location='index.php';</script>";
    exit;
}

// Mengambil data barang yang akan diedit dari database (Join dengan tabel stok)
$sql = "
    SELECT b.*, st.jumlah_stok, st.stok_minimum 
    FROM barang b 
    LEFT JOIN stok st ON b.id_barang = st.id_barang 
    WHERE b.id_barang = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $id_barang);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<script>alert('Data barang tidak ditemukan!'); window.location='index.php';</script>";
    exit;
}

$data = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Barang</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>

<body>
    <div class="wrapper d-flex max-vh-100">
        
        <div>
            <?php include '../templates/sidebar.php'; ?>
        </div>
        
        <div class="main-content">
            <div class="content-body">
                <div class="mb-4">
                    <h2 class="fw-bold mb-1">Edit Data Barang</h2>
                    <p class="text-muted mb-3">Perbarui informasi barang dan stok pada inventory</p>
                    
                    <div class="card border-0 rounded-4 shadow-sm">
                        <div class="card-body p-4.5">
                            
                            <form action="proses_edit_barang.php" method="POST">
                                
                                <input type="hidden" name="id_barang" value="<?= htmlspecialchars($data['id_barang']); ?>">

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Nama Barang</label>
                                    <input
                                        type="text"
                                        name="nama_barang"
                                        class="form-control"
                                        style="border: 1px solid #7a7a7a; border-radius: 8px;"
                                        value="<?= htmlspecialchars($data['nama_barang']); ?>"
                                        required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Kategori</label>
                                    <select name="kategori" class="form-select" style="border: 1px solid #7a7a7a; border-radius: 8px;" required>
                                        <option value="">-- Pilih Kategori --</option>
                                        <option value="Makanan" <?= ($data['kategori'] == 'Makanan') ? 'selected' : ''; ?>>Makanan</option>
                                        <option value="Minuman" <?= ($data['kategori'] == 'Minuman') ? 'selected' : ''; ?>>Minuman</option>
                                        <option value="Sembako" <?= ($data['kategori'] == 'Sembako') ? 'selected' : ''; ?>>Sembako</option>
                                        <option value="Kebutuhan" <?= ($data['kategori'] == 'Kebutuhan') ? 'selected' : ''; ?>>Kebutuhan</option>
                                    </select>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6 mb-3 mb-md-0">
                                        <label class="form-label fw-semibold">Harga Beli (Rp)</label>
                                        <input
                                            type="number"
                                            name="harga_beli"
                                            class="form-control"
                                            style="border: 1px solid #7a7a7a; border-radius: 8px;"
                                            min="0"
                                            value="<?= $data['harga_beli']; ?>"
                                            required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Harga Jual (Rp)</label>
                                        <input
                                            type="number"
                                            name="harga_jual"
                                            class="form-control"
                                            style="border: 1px solid #7a7a7a; border-radius: 8px;"
                                            min="0"
                                            value="<?= $data['harga_jual']; ?>"
                                            required>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-4 mb-3 mb-md-0">
                                        <label class="form-label fw-semibold">Satuan</label>
                                        <input
                                            type="text"
                                            name="satuan"
                                            placeholder="Contoh: pcs, renteng, kg"
                                            class="form-control"
                                            style="border: 1px solid #7a7a7a; border-radius: 8px;"
                                            value="<?= htmlspecialchars($data['satuan']); ?>"
                                            required>
                                    </div>

                                    <div class="col-md-4 mb-3 mb-md-0">
                                        <label class="form-label fw-semibold">Stok Saat Ini</label>
                                        <input
                                            type="number"
                                            name="jumlah_stok"
                                            class="form-control"
                                            style="border: 1px solid #7a7a7a; border-radius: 8px;"
                                            value="<?= $data['jumlah_stok'] ?? 0; ?>"
                                            min="0"
                                            required>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Stok Minimum</label>
                                        <input
                                            type="number"
                                            name="stok_minimum"
                                            class="form-control"
                                            style="border: 1px solid #7a7a7a; border-radius: 8px;"
                                            value="<?= $data['stok_minimum'] ?? 5; ?>"
                                            min="0"
                                            required>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Status Barang</label>
                                    <select name="status_barang" class="form-select" style="border: 1px solid #7a7a7a; border-radius: 8px;">
                                        <option value="aktif" <?= ($data['status_barang'] == 'aktif') ? 'selected' : ''; ?>>Aktif</option>
                                        <option value="nonaktif" <?= ($data['status_barang'] == 'nonaktif') ? 'selected' : ''; ?>>Nonaktif</option>
                                    </select>
                                </div>

                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <a href="index.php" class="btn btn-outline-secondary rounded-4 px-4">
                                        Batal
                                    </a>
                                    <button type="submit" class="btn btn-primary rounded-4 px-4">
                                        <i class="fa-solid fa-floppy-disk me-2"></i>Simpan Perubahan
                                    </button>
                                </div>

                            </form>

                        </div>
                    </div> 

                </div>
            </div> 
        </div> 
    </div> 
    <?php include '../templates/footer.php'; ?>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>