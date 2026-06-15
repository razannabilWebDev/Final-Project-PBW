<?php
session_start();

// 1. Proteksi Halaman & Validasi Role sesuai Template Utama
if (!isset($_SESSION['role'])) {
    header("Location: ../login.php"); 
    exit;
}

$role_sekarang = $_SESSION['role']; 

// Menggunakan berkas koneksi bawaan
include 'koneksi.php';

// Mengambil data barang (termasuk harga_beli)
$barang = $conn->query("
    SELECT * FROM barang
    WHERE status_barang = 'aktif'
");

$supplier = $conn->query("
    SELECT * FROM supplier
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembelian Barang</title>

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
                    <h2 class="fw-bold mb-1">Pembelian Barang</h2>
                    <p class="text-muted mb-3">Formulir pencatatan transaksi pembelian barang dari supplier</p>
                    
                    <div class="card border-0 rounded-4 shadow-sm">
                        <div class="card-body p-4.5">
                            
                            <form action="proses_pembelian.php" method="POST">

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Supplier</label>
                                    <select name="id_supplier" class="form-select" style="border: 1px solid #7a7a7a; border-radius: 8px;" required>
                                        <option value="">-- Pilih Supplier --</option>
                                        <?php while($s = $supplier->fetch_assoc()) : ?>
                                            <option value="<?= $s['id_supplier']; ?>">
                                                <?= htmlspecialchars($s['nama_supplier']); ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Barang</label>
                                    <select name="id_barang" id="pilih_barang" class="form-select" style="border: 1px solid #7a7a7a; border-radius: 8px;" required>
                                        <option value="" data-harga="">-- Pilih Barang --</option>
                                        <?php while($b = $barang->fetch_assoc()) : ?>
                                            <option value="<?= $b['id_barang']; ?>" data-harga="<?= $b['harga_beli']; ?>">
                                                <?= htmlspecialchars($b['nama_barang']); ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Jumlah</label>
                                    <input 
                                        type="number"
                                        name="jumlah"
                                        class="form-control"
                                        style="border: 1px solid #7a7a7a; border-radius: 8px;"
                                        min="1"
                                        required>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Harga Beli Satuan (Rp)</label>
                                    <input 
                                        type="number"
                                        name="harga_beli"
                                        id="harga_beli"
                                        class="form-control"
                                        style="border: 1px solid #7a7a7a; border-radius: 8px;"
                                        min="0"
                                        required>
                                    <div class="form-text text-muted"><i class="fa-solid fa-circle-info me-1"></i>Harga beli terisi otomatis berdasarkan data barang, namun tetap bisa diubah jika ada kenaikan/penurunan harga dari supplier.</div>
                                </div>

                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <a href="index.php" class="btn btn-outline-secondary rounded-4 px-4">
                                        Batal
                                    </a>
                                    <button type="submit" class="btn btn-primary rounded-4 px-4">
                                        <i class="fa-solid fa-floppy-disk me-2"></i>Simpan Pembelian
                                    </button>
                                </div>

                            </form>

                        </div>
                    </div> 

                </div>
            </div> 
        </div> 
    </div> 

    <script>
        // Menangkap elemen dropdown barang dan input harga beli
        const pilihBarang = document.getElementById('pilih_barang');
        const inputHargaBeli = document.getElementById('harga_beli');

        // Menambahkan event listener saat dropdown berubah (dipilih)
        pilihBarang.addEventListener('change', function() {
            // Mengambil opsi (option) yang sedang dipilih user
            const selectedOption = this.options[this.selectedIndex];
            
            // Mengambil nilai dari atribut 'data-harga'
            const harga = selectedOption.getAttribute('data-harga');
            
            // Memasukkan nilai tersebut ke dalam kolom input harga beli
            // Jika kosong (misal kembali ke "-- Pilih Barang --"), maka kosongkan input
            inputHargaBeli.value = harga ? harga : '';
        });
    </script>
    <?php include '../templates/footer.php'; ?>
</body>
</html>