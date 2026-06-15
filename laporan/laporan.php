<?php
session_start();

include '../config/session.php';
include '../config/koneksi.php';

cek_login_admin();

/*
|--------------------------------------------------------------------------
| FILTER TANGGAL
|--------------------------------------------------------------------------
*/

$tanggal_awal  = $_GET['tanggal_awal'] ?? '';
$tanggal_akhir = $_GET['tanggal_akhir'] ?? '';

$where = "";

if($tanggal_awal && $tanggal_akhir){

    $where = "
        WHERE DATE(transaksi.tanggal)
        BETWEEN '$tanggal_awal'
        AND '$tanggal_akhir'
    ";
}

/*
|--------------------------------------------------------------------------
| QUERY LAPORAN
|--------------------------------------------------------------------------
*/

$query_laporan = mysqli_query($conn,"
    SELECT
        transaksi.id_transaksi,
        transaksi.tanggal,
        transaksi.total_harga,
        transaksi.bayar,
        transaksi.kembalian,

        pelanggan.nama_pelanggan,

        user.username

    FROM transaksi

    LEFT JOIN pelanggan
    ON transaksi.id_pelanggan = pelanggan.id_pelanggan

    LEFT JOIN user
    ON transaksi.id_user = user.id_user

    $where

    ORDER BY transaksi.id_transaksi DESC
");

/*
|--------------------------------------------------------------------------
| TOTAL PENDAPATAN
|--------------------------------------------------------------------------
*/

$query_total = mysqli_query($conn,"
    SELECT
        SUM(detail_transaksi.subtotal) AS total

    FROM detail_transaksi

    JOIN transaksi
    ON detail_transaksi.id_transaksi = transaksi.id_transaksi

    $where
");

$total = mysqli_fetch_assoc($query_total);

/*
|--------------------------------------------------------------------------
| LABA / RUGI
|--------------------------------------------------------------------------
*/

$query_keuntungan = mysqli_query($conn,"
    SELECT
        SUM(
            (detail_transaksi.harga_jual - barang.harga_beli)
            * detail_transaksi.jumlah
        ) AS total_keuntungan

    FROM detail_transaksi

    JOIN barang
    ON detail_transaksi.id_barang = barang.id_barang

    JOIN transaksi
    ON detail_transaksi.id_transaksi = transaksi.id_transaksi

    $where
");

$data_keuntungan = mysqli_fetch_assoc($query_keuntungan);

$total_keuntungan = $data_keuntungan['total_keuntungan'] ?? 0;

/*
|--------------------------------------------------------------------------
| TOTAL TRANSAKSI
|--------------------------------------------------------------------------
*/

$query_total_transaksi = mysqli_query($conn,"
    SELECT COUNT(*) AS jumlah
    FROM transaksi
    $where
");

$total_transaksi = mysqli_fetch_assoc($query_total_transaksi);

/*
|--------------------------------------------------------------------------
| TOTAL ITEM TERJUAL
|--------------------------------------------------------------------------
*/

$query_item_terjual = mysqli_query($conn,"
    SELECT SUM(jumlah) AS total_item
    FROM detail_transaksi
");

$item_terjual = mysqli_fetch_assoc($query_item_terjual);

/*
|--------------------------------------------------------------------------
| DATA GRAFIK
|--------------------------------------------------------------------------
*/

$query_chart = mysqli_query($conn,"
    SELECT
        DATE(transaksi.tanggal) AS tanggal,
        SUM(detail_transaksi.subtotal) AS total_harian

    FROM transaksi

    JOIN detail_transaksi
    ON transaksi.id_transaksi = detail_transaksi.id_transaksi

    $where

    GROUP BY DATE(transaksi.tanggal)

    ORDER BY transaksi.tanggal ASC
");

$labels = [];
$data_chart = [];

while($chart = mysqli_fetch_assoc($query_chart)){

    $labels[] = date(
        'd M',
        strtotime($chart['tanggal'])
    );

    $data_chart[] = $chart['total_harian'];
}

/*
|--------------------------------------------------------------------------
| PRODUK TERLARIS
|--------------------------------------------------------------------------
*/

$query_produk_terlaris = mysqli_query($conn,"
    SELECT
        barang.nama_barang,
        SUM(detail_transaksi.jumlah) AS total_terjual

    FROM detail_transaksi

    JOIN barang
    ON detail_transaksi.id_barang = barang.id_barang

    GROUP BY detail_transaksi.id_barang

    ORDER BY total_terjual DESC

    LIMIT 5
");

?>

<!DOCTYPE html>
<html lang="id">
<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        Laporan Penjualan
    </title>

    <!-- Bootstrap -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <!-- Font Awesome -->

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Google Fonts -->

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
          rel="stylesheet">

    <!-- Chart JS -->

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- CSS -->

    <link rel="stylesheet"
          href="../assets/css/laporan.css">

</head>

<body>

<!-- SIDEBAR -->

<?php include '../templates/sidebar.php'; ?>

<div class="page-wrapper">

    <!-- HEADER -->

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">

        <div>

            <h2 class="page-title">
                Laporan Penjualan
            </h2>

            <p class="text-muted mb-0">
                Monitoring transaksi dan pendapatan warung
            </p>

        </div>

        <!-- CETAK PDF -->

        <a href="cetak_laporan.php?tanggal_awal=<?= $tanggal_awal ?>&tanggal_akhir=<?= $tanggal_akhir ?>"
           target="_blank"
           class="btn btn-danger rounded-4 px-4 py-2">

            <i class="fas fa-file-pdf me-2"></i>

            Cetak PDF

        </a>

    </div>

    <!-- FILTER -->

    <div class="card-custom">

        <form method="GET">

            <div class="row g-3 align-items-end">

                <div class="col-md-4">

                    <label class="form-label fw-semibold">
                        Tanggal Awal
                    </label>

                    <input type="date"
                           name="tanggal_awal"
                           class="form-control rounded-4"
                           value="<?= $tanggal_awal ?>">

                </div>

                <div class="col-md-4">

                    <label class="form-label fw-semibold">
                        Tanggal Akhir
                    </label>

                    <input type="date"
                           name="tanggal_akhir"
                           class="form-control rounded-4"
                           value="<?= $tanggal_akhir ?>">

                </div>

                <div class="col-md-4">

                    <button type="submit"
                            class="btn btn-primary-custom w-100">

                        <i class="fas fa-filter me-2"></i>

                        Filter Laporan

                    </button>

                </div>

            </div>

        </form>

    </div>

    <!-- STATISTIK -->

    <div class="row g-4 mb-4">

        <!-- TOTAL PENDAPATAN -->

        <div class="col-lg-4">

            <div class="card-custom h-100">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <p class="text-muted mb-1">
                            Total Pendapatan
                        </p>

                        <h3 class="fw-bold text-success">

                            Rp <?= number_format(
                                $total['total'] ?? 0,
                                0,
                                ',',
                                '.'
                            ); ?>

                        </h3>

                    </div>

                    <div class="icon-box bg-success-subtle text-success">

                        <i class="fas fa-wallet"></i>

                    </div>

                </div>

            </div>

        </div>

        <!-- TOTAL TRANSAKSI -->

        <div class="col-lg-4">

            <div class="card-custom h-100">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <p class="text-muted mb-1">
                            Total Transaksi
                        </p>

                        <h3 class="fw-bold text-primary">

                            <?= $total_transaksi['jumlah']; ?>

                        </h3>

                    </div>

                    <div class="icon-box bg-primary-subtle text-primary">

                        <i class="fas fa-cart-shopping"></i>

                    </div>

                </div>

            </div>

        </div>

        <!-- ITEM TERJUAL -->

        <div class="col-lg-4">

            <div class="card-custom h-100">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <p class="text-muted mb-1">
                            Total Item Terjual
                        </p>

                        <h3 class="fw-bold text-warning">

                            <?= $item_terjual['total_item'] ?? 0; ?>

                        </h3>

                    </div>

                    <div class="icon-box bg-warning-subtle text-warning">

                        <i class="fas fa-box"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- LABA / RUGI -->

    <div class="col-lg-4">

        <div class="card-custom h-100">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <p class="text-muted mb-1">
                        Status Keuangan
                    </p>

                    <?php if($total_keuntungan > 0): ?>

                        <h3 class="fw-bold text-success">

                            Untung

                        </h3>

                        <small class="text-success">

                            + Rp <?= number_format(
                                $total_keuntungan,
                                0,
                                ',',
                                '.'
                            ); ?>

                        </small>

                    <?php elseif($total_keuntungan < 0): ?>

                        <h3 class="fw-bold text-danger">

                            Rugi

                        </h3>

                        <small class="text-danger">

                            Rp <?= number_format(
                                $total_keuntungan,
                                0,
                                ',',
                                '.'
                            ); ?>

                        </small>

                    <?php else: ?>

                        <h3 class="fw-bold text-secondary">

                            Impas

                        </h3>

                        <small class="text-muted">
                            Tidak ada keuntungan
                        </small>

                    <?php endif; ?>

                </div>

                <div class="icon-box 
                    <?= $total_keuntungan > 0 ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger'; ?>">

                    <i class="fas fa-chart-line"></i>

                </div>

            </div>

        </div>

    </div>

    <!-- GRAFIK -->

    <div class="card-custom mb-4">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <h5 class="fw-bold mb-0">

                Grafik Pendapatan

            </h5>

            <div class="badge bg-success">
                Data Penjualan
            </div>

        </div>

        <canvas id="chartPenjualan"></canvas>

    </div>

    <!-- PRODUK TERLARIS -->

    <div class="card-custom mb-4">

        <h5 class="fw-bold mb-4">

            Produk Terlaris

        </h5>

        <div class="table-responsive">

            <table class="table align-middle">

                <thead>

                    <tr>

                        <th>No</th>

                        <th>Nama Barang</th>

                        <th>Total Terjual</th>

                    </tr>

                </thead>

                <tbody>

                <?php
                $no_produk = 1;
                while($produk = mysqli_fetch_assoc($query_produk_terlaris)):
                ?>

                    <tr>

                        <td>
                            <?= $no_produk++; ?>
                        </td>

                        <td>

                            <?= $produk['nama_barang']; ?>

                        </td>

                        <td>

                            <span class="badge bg-success">

                                <?= $produk['total_terjual']; ?> Item

                            </span>

                        </td>

                    </tr>

                <?php endwhile; ?>

                </tbody>

            </table>

        </div>

    </div>

    <!-- DATA PENJUALAN -->

    <div class="card-custom">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <h5 class="fw-bold mb-0">

                Data Penjualan

            </h5>

        </div>

        <div class="table-responsive">

            <table class="table align-middle table-hover">

                <thead>

                    <tr>

                        <th>No</th>

                        <th>ID</th>

                        <th>Tanggal</th>

                        <th>Pelanggan</th>

                        <th>Kasir</th>

                        <th>Total</th>

                        <th>Bayar</th>

                        <th>Kembalian</th>

                    </tr>

                </thead>

                <tbody>

                <?php
                $no = 1;

                while($row = mysqli_fetch_assoc($query_laporan)):
                ?>

                    <tr>

                        <td>
                            <?= $no++; ?>
                        </td>

                        <td>

                            <span class="fw-semibold text-success">

                                #TRX<?= $row['id_transaksi']; ?>

                            </span>

                        </td>

                        <td>

                            <?= date(
                                'd M Y H:i',
                                strtotime($row['tanggal'])
                            ); ?>

                        </td>

                        <td>

                            <?= $row['nama_pelanggan']; ?>

                        </td>

                        <td>

                            <?= $row['username']; ?>

                        </td>

                        <td class="fw-bold text-success">

                            Rp <?= number_format(
                                $row['total_harga'],
                                0,
                                ',',
                                '.'
                            ); ?>

                        </td>

                        <td>

                            Rp <?= number_format(
                                $row['bayar'],
                                0,
                                ',',
                                '.'
                            ); ?>

                        </td>

                        <td>

                            Rp <?= number_format(
                                $row['kembalian'],
                                0,
                                ',',
                                '.'
                            ); ?>

                        </td>

                    </tr>

                <?php endwhile; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<!-- CHART -->

<script>

const ctx = document.getElementById('chartPenjualan');

new Chart(ctx, {

    type: 'line',

    data: {

        labels: <?= json_encode($labels) ?>,

        datasets: [{

            label: 'Pendapatan',

            data: <?= json_encode($data_chart) ?>,

            borderWidth: 3,

            tension: 0.4,

            fill: true

        }]

    },

    options: {

        responsive: true,

        plugins: {

            legend: {

                display: true

            }

        },

        scales: {

            y: {

                beginAtZero: true

            }

        }

    }

});

</script>

<?php include '../templates/footer.php'; ?>

</body>
</html>