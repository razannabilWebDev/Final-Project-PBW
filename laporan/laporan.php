<?php
session_start();
include '../config/session.php';
include '../config/koneksi.php';
cek_login_admin();


// Filter tanggal
$tanggal_awal = $_GET['tanggal_awal'] ?? '';
$tanggal_akhir = $_GET['tanggal_akhir'] ?? '';

$where = "";

if($tanggal_awal && $tanggal_akhir){
    $where = "WHERE transaksi.tanggal BETWEEN '$tanggal_awal' AND '$tanggal_akhir'";
}

// Query laporan
$query_laporan = mysqli_query($conn, "
    SELECT
        transaksi.id_transaksi,
        transaksi.tanggal,
        pelanggan.nama_pelanggan,
        SUM(detail_transaksi.subtotal) as total_harga
    FROM transaksi
    JOIN pelanggan
    ON transaksi.id_pelanggan = pelanggan.id_pelanggan
    JOIN detail_transaksi
    ON transaksi.id_transaksi = detail_transaksi.id_transaksi
    $where
    GROUP BY transaksi.id_transaksi
    ORDER BY transaksi.id_transaksi DESC
");

// Total pendapatan
$query_total = mysqli_query($conn, "
    SELECT SUM(subtotal) as total
    FROM detail_transaksi
    JOIN transaksi
    ON detail_transaksi.id_transaksi = transaksi.id_transaksi
    $where
");

$total = mysqli_fetch_assoc($query_total);

// Data grafik
$query_chart = mysqli_query($conn, "
    SELECT
        tanggal,
        SUM(detail_transaksi.subtotal) as total_harian
    FROM transaksi
    JOIN detail_transaksi
    ON transaksi.id_transaksi = detail_transaksi.id_transaksi
    $where
    GROUP BY transaksi.tanggal
    ORDER BY tanggal ASC
");

$labels = [];
$data_chart = [];

while($chart = mysqli_fetch_assoc($query_chart)){
    $labels[] = date('d M', strtotime($chart['tanggal']));
    $data_chart[] = $chart['total_harian'];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="../assets/css/laporan.css">
</head>
<body>
    <?php include '../templates/sidebar.php' ?>

<div class="page-wrapper">

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h2 class="page-title">Laporan Penjualan</h2>
            <p class="text-muted mb-0">Monitoring transaksi dan pendapatan warung</p>
        </div>

        <a href="cetak_laporan.php?tanggal_awal=<?= $tanggal_awal ?>&tanggal_akhir=<?= $tanggal_akhir ?>"
           target="_blank"
           class="btn btn-danger rounded-4 px-4 py-2">

            <i class="fas fa-file-pdf me-2"></i>
            Cetak PDF

        </a>
    </div>

    <div class="card-custom">

        <form method="GET">

            <div class="row g-3 align-items-end">

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Tanggal Awal</label>
                    <input type="date"
                           name="tanggal_awal"
                           class="form-control rounded-4"
                           value="<?= $tanggal_awal ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Tanggal Akhir</label>
                    <input type="date"
                           name="tanggal_akhir"
                           class="form-control rounded-4"
                           value="<?= $tanggal_akhir ?>">
                </div>

                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary-custom w-100">
                        <i class="fas fa-filter me-2"></i>
                        Filter Laporan
                    </button>
                </div>

            </div>

        </form>

    </div>

    <div class="card-custom">

        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <h5 class="fw-bold mb-0">Total Pendapatan</h5>

            <div class="badge-total">
                Rp <?= number_format($total['total'] ?? 0,0,',','.') ?>
            </div>
        </div>

        <canvas id="chartPenjualan"></canvas>

    </div>

    <div class="card-custom">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-bold mb-0">Data Penjualan</h5>
        </div>

        <div class="table-responsive">

            <table class="table align-middle table-hover">

                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID Transaksi</th>
                        <th>Tanggal</th>
                        <th>Pelanggan</th>
                        <th>Total</th>
                    </tr>
                </thead>

                <tbody>

                    <?php
                    $no = 1;
                    while($row = mysqli_fetch_assoc($query_laporan)) :
                    ?>

                    <tr>
                        <td><?= $no++ ?></td>

                        <td>
                            <span class="fw-semibold text-success">
                                #TRX<?= $row['id_transaksi'] ?>
                            </span>
                        </td>

                        <td>
                            <?= date('d M Y', strtotime($row['tanggal'])) ?>
                        </td>

                        <td><?= $row['nama_pelanggan'] ?></td>

                        <td class="fw-bold text-success">
                            Rp <?= number_format($row['total_harga'],0,',','.') ?>
                        </td>
                    </tr>

                    <?php endwhile; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

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
<?php include '../templates/footer.php' ?>
</body>
</html>