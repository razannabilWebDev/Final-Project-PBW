<?php
session_start();
require '../config/koneksi.php';

if (!isset($_SESSION['keranjang'])) {
    $_SESSION['keranjang'] = [];
}

$pelanggan = mysqli_query($conn, "
    SELECT *
    FROM pelanggan
    ORDER BY nama_pelanggan ASC
");

$total = 0;
$total_item = 0;

foreach ($_SESSION['keranjang'] as $item) {
    $total += $item['subtotal'];
    $total_item += $item['qty'];
}
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <title>Transaksi Penjualan</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>

   <?php include "../templates/sidebar.php" ?>

    <div class="main-content">

        <h3 class="fw-bold">Transaksi Penjualan</h3>

        <p class="text-muted">
            Selamat datang di sistem informasi warung kelontong
        </p>

        <div class="row g-4">

            <!-- KIRI -->

            <div class="col-lg-7">

                <div class="card card-pos mb-4">

                    <div class="card-body p-4">

                        <div class="d-flex gap-3 align-items-center mb-4">
                            <div class="step-badge">1</div>
                            <h5 class="mb-0">Input Barang</h5>
                        </div>

                        <form action="tambah_keranjang.php" method="POST">

                            <label class="fw-semibold mb-2">
                                Cari Barang
                            </label>

                            <input type="text"
                                id="keyword"
                                class="form-control mb-3"
                                placeholder="Cari nama produk">

                            <select name="id_barang"
                                id="id_barang"
                                class="form-select mb-3"
                                required>
                            </select>

                            <div class="row">

                                <div class="col-md-4">

                                    <label>Jumlah (Qty)</label>

                                    <input type="number"
                                        name="qty"
                                        class="form-control"
                                        value="1"
                                        min="1"
                                        required>

                                </div>

                                <div class="col-md-8 d-flex align-items-end">

                                    <button class="btn btn-main w-100">

                                        <i class="bi bi-cart-plus"></i>

                                        Tambah ke Keranjang

                                    </button>

                                </div>

                            </div>

                        </form>

                    </div>

                </div>

                <div class="card card-pos">

                    <div class="card-body p-4">

                        <div class="d-flex gap-3 align-items-center mb-4">
                            <div class="step-badge">2</div>
                            <h5 class="mb-0">Daftar Belanjaan</h5>
                        </div>

                        <div class="table-responsive">

                            <table class="table align-middle">

                                <thead>

                                    <tr>
                                        <th>No</th>
                                        <th>Nama Barang</th>
                                        <th>Qty</th>
                                        <th>Harga</th>
                                        <th>Subtotal</th>
                                        <th>Aksi</th>
                                    </tr>

                                </thead>

                                <tbody>

                                    <?php if(empty($_SESSION['keranjang'])) : ?>

                                        <tr>
                                            <td colspan="6" class="text-center">
                                                Keranjang kosong
                                            </td>
                                        </tr>

                                    <?php endif; ?>

                                    <?php foreach($_SESSION['keranjang'] as $key => $item) : ?>

                                        <tr>

                                            <td><?= $key + 1 ?></td>

                                            <td><?= $item['nama_barang'] ?></td>

                                            <td>

                                                <form action="update_keranjang.php" method="POST" class="d-flex gap-2">

                                                    <input type="hidden"
                                                        name="index"
                                                        value="<?= $key ?>">

                                                    <input type="number"
                                                        name="qty"
                                                        class="form-control qty-input"
                                                        value="<?= $item['qty'] ?>"
                                                        min="1">

                                                    <button class="btn btn-warning btn-sm">

                                                        <i class="bi bi-arrow-repeat"></i>

                                                    </button>

                                                </form>

                                            </td>

                                            <td>
                                                Rp <?= number_format($item['harga']) ?>
                                            </td>

                                            <td>
                                                Rp <?= number_format($item['subtotal']) ?>
                                            </td>

                                            <td>

                                                <a href="hapus_keranjang.php?index=<?= $key ?>"
                                                    class="btn btn-danger btn-sm">

                                                    <i class="bi bi-trash"></i>

                                                </a>

                                            </td>

                                        </tr>

                                    <?php endforeach; ?>

                                </tbody>

                            </table>

                        </div>

                        <div class="alert alert-light border mb-0">

                            Total Item:

                            <strong><?= $total_item ?> Barang</strong>

                        </div>

                    </div>

                </div>

            </div>

            <!-- KANAN -->

            <div class="col-lg-5">

                <div class="card card-pos">

                    <div class="card-body p-4">

                        <div class="d-flex gap-3 align-items-center mb-4">
                            <div class="step-badge">3</div>
                            <h5 class="mb-0">Input Pelanggan</h5>
                        </div>

                        <form action="simpan.php" method="POST">

                            <label class="fw-semibold mb-2">
                                Pilih Pelanggan
                            </label>

                            <select name="id_pelanggan"
                                id="id_pelanggan"
                                class="form-select mb-3"
                                required>

                                <?php while($p = mysqli_fetch_assoc($pelanggan)) : ?>

                                    <option
                                        value="<?= $p['id_pelanggan'] ?>"
                                        data-poin="<?= $p['poin_member'] ?>">

                                        <?= $p['nama_pelanggan'] ?>

                                        (<?= $p['poin_member'] ?> poin)

                                    </option>

                                <?php endwhile; ?>

                            </select>

                            <div class="card mb-3">

                                <div class="card-body">

                                    <div class="row mb-2">

                                        <div class="col-6">
                                            Poin Saat Ini
                                        </div>

                                        <div class="col-6">
                                            : <span id="poin">0</span>
                                        </div>

                                    </div>

                                </div>

                            </div>

                            <div class="alert alert-primary">

                                Minimal transaksi Rp100.000 mendapat +10 poin

                            </div>

                            <div class="total-box mb-3">

                                <small>TOTAL BELANJA</small>

                                <h2>
                                    Rp <?= number_format($total) ?>
                                </h2>

                            </div>

                            <input type="hidden"
                                id="total"
                                value="<?= $total ?>">

                            <div class="mb-3">

                                <label>Pembayaran</label>

                                <input type="number"
                                    name="bayar"
                                    id="bayar"
                                    class="form-control"
                                    required>

                            </div>

                            <div class="total-box mb-4">

                                <small>Kembalian</small>

                                <h2 id="kembalian">

                                    Rp 0

                                </h2>

                            </div>

                            <div class="row">

                                <div class="col-6">

                                    <button
                                        type="submit"
                                        name="aksi"
                                        value="cetak"
                                        class="btn btn-main w-100">

                                        <i class="bi bi-printer"></i>

                                        Simpan & Cetak

                                    </button>

                                </div>

                                <div class="col-6">

                                    <button
                                        type="submit"
                                        name="aksi"
                                        value="simpan"
                                        class="btn btn-outline-secondary w-100">

                                        <i class="bi bi-floppy"></i>

                                        Simpan

                                    </button>

                                </div>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <script>

        // LOAD BARANG

        function loadBarang(keyword = '') {

            fetch('cari_barang.php?keyword=' + keyword)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('id_barang').innerHTML = data;
                });

        }

        loadBarang();

        document.getElementById('keyword')
            .addEventListener('keyup', function() {

                loadBarang(this.value);

            });

        // POIN PELANGGAN

        const pelanggan = document.getElementById('id_pelanggan');

        function tampilPoin() {

            let poin = pelanggan.options[pelanggan.selectedIndex]
                .dataset.poin;

            document.getElementById('poin').innerText = poin;

        }

        tampilPoin();

        pelanggan.addEventListener('change', tampilPoin);

        // KEMBALIAN

        document.getElementById('bayar')
            .addEventListener('keyup', function() {

                let bayar = parseInt(this.value) || 0;

                let total = parseInt(
                    document.getElementById('total').value
                );

                let kembali = bayar - total;

                if (kembali < 0) kembali = 0;

                document.getElementById('kembalian')
                    .innerText = 'Rp ' + kembali.toLocaleString('id-ID');

            });

    </script>

</body>

</html>