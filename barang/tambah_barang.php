<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="style.css">

<div class="content p-4">
    <div class="card-custom">
        <h2 class="title mb-4">
            <i class="bi bi-plus-circle-fill"></i> Tambah Barang Baru
        </h2>

        <form action="proses_tambah_barang.php" method="POST">
            <div class="mb-3">
                <label class="form-label">Nama Barang</label>
                <input type="text" name="nama_barang" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Kategori</label>
                <input type="text" name="kategori" class="form-control" required>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Harga Beli (Rp)</label>
                    <input type="number" name="harga_beli" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Harga Jual (Rp)</label>
                    <input type="number" name="harga_jual" class="form-control" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">Satuan</label>
                    <input type="text" name="satuan" placeholder="Contoh: pcs, renteng, kg" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Stok Awal</label>
                    <input type="number" name="jumlah_stok" class="form-control" value="0" min="0" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Stok Minimum</label>
                    <input type="number" name="stok_minimum" class="form-control" value="5" min="0" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Status Barang</label>
                <select name="status_barang" class="form-select">
                    <option value="aktif">Aktif</option>
                    <option value="nonaktif">Nonaktif</option>
                </select>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-modern">
                    <i class="bi bi-save-fill"></i> Simpan Barang & Stok
                </button>
                <a href="index.php" class="btn btn-light rounded-4">Batal</a>
            </div>
        </form>
    </div>
</div>