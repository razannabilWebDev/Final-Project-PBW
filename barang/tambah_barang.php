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
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Barang Baru</title>

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
                    <h2 class="fw-bold mb-1">Tambah Barang Baru</h2>
                    <p class="text-muted mb-3">Formulir pendaftaran barang baru beserta stok awal</p>
                    
                    <!-- FORM CONTAINER CARD -->
                    <div class="card border-0 rounded-4 shadow-sm">
                        <div class="card-body p-4.5">
                            
                            <form action="proses_tambah_barang.php" method="POST">

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Nama Barang</label>
                                    <input
                                        type="text"
                                        name="nama_barang"
                                        class="form-control"
                                        style="border: 1px solid #7a7a7a; border-radius: 8px;"
                                        required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Kategori</label>
                                    <select name="kategori" class="form-select" style="border: 1px solid #7a7a7a; border-radius: 8px;" required>
                                        <option value="">-- Pilih Kategori --</option>
                                        <option value="Makanan">Makanan</option>
                                        <option value="Minuman">Minuman</option>
                                        <option value="Sembako">Sembako</option>
                                        <option value="Kebutuhan">Kebutuhan</option>
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
                                            required>
                                    </div>

                                    <div class="col-md-4 mb-3 mb-md-0">
                                        <label class="form-label fw-semibold">Stok Awal</label>
                                        <input
                                            type="number"
                                            name="jumlah_stok"
                                            class="form-control"
                                            style="border: 1px solid #7a7a7a; border-radius: 8px;"
                                            value="0"
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
                                            value="5"
                                            min="0"
                                            required>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Status Barang</label>
                                    <select name="status_barang" class="form-select" style="border: 1px solid #7a7a7a; border-radius: 8px;">
                                        <option value="aktif">Aktif</option>
                                        <option value="nonaktif">Nonaktif</option>
                                    </select>
                                </div>

                                <!-- BUTTON ACTIONS -->
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary rounded-4 px-4">
                                        <i class="fa-solid fa-floppy-disk me-2"></i>Simpan Barang & Stok
                                    </button>

                                    <a href="index.php" class="btn btn-outline-secondary rounded-4 px-4">
                                        Batal
                                    </a>
                                </div>

                            </form>

                        </div>
                    </div> 

                </div>
            </div> 
            
            <!-- FOOTER COMPONENT -->
            <?php include '../templates/footer.php'; ?>
        </div> 
    </div> 
</body>
</html>