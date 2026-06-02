<?php
session_start();

require '../config/koneksi.php';
require '../config/session.php';

cek_login_admin();

/*
|--------------------------------------------------------------------------
| TOTAL DATA
|--------------------------------------------------------------------------
*/

$total_barang = mysqli_num_rows(
    mysqli_query($conn, "SELECT * FROM barang")
);

$total_supplier = mysqli_num_rows(
    mysqli_query($conn, "SELECT * FROM supplier")
);

$total_pelanggan = mysqli_num_rows(
    mysqli_query($conn, "SELECT * FROM pelanggan")
);

$total_transaksi = mysqli_num_rows(
    mysqli_query($conn, "SELECT * FROM transaksi")
);

$total_user = mysqli_num_rows(
    mysqli_query($conn, "SELECT * FROM user")
);

/*
|--------------------------------------------------------------------------
| TOTAL STOK
|--------------------------------------------------------------------------
*/

$query_total_stok = mysqli_query($conn,"
    SELECT SUM(jumlah_stok) AS total_stok
    FROM stok
");

$data_total_stok = mysqli_fetch_assoc($query_total_stok);

$total_stok = $data_total_stok['total_stok'];

/*
|--------------------------------------------------------------------------
| PENDAPATAN HARIAN
|--------------------------------------------------------------------------
*/

function pendapatan_harian($conn){

    $query = mysqli_query($conn,"
        SELECT SUM(detail_transaksi.subtotal) AS total
        FROM detail_transaksi
        JOIN transaksi
        ON detail_transaksi.id_transaksi = transaksi.id_transaksi
        WHERE DATE(transaksi.tanggal) = CURDATE()
    ");

    $data = mysqli_fetch_assoc($query);

    return $data['total'] ?? 0;
}

/*
|--------------------------------------------------------------------------
| TOTAL PENDAPATAN
|--------------------------------------------------------------------------
*/

function pendapatan_total($conn){

    $query = mysqli_query($conn,"
        SELECT SUM(subtotal) AS total_pendapatan
        FROM detail_transaksi
    ");

    $data = mysqli_fetch_assoc($query);

    return $data['total_pendapatan'] ?? 0;
}

/*
|--------------------------------------------------------------------------
| TOTAL PEMBELIAN
|--------------------------------------------------------------------------
*/

function total_pembelian($conn){

    $query = mysqli_query($conn,"
        SELECT SUM(total_pembelian) AS total_belanja
        FROM pembelian
    ");

    $data = mysqli_fetch_assoc($query);

    return $data['total_belanja'] ?? 0;
}

$total_harian = pendapatan_harian($conn);

$total_pendapatan = pendapatan_total($conn);

$total_belanja = total_pembelian($conn);

/*
|--------------------------------------------------------------------------
| BARANG TERBARU
|--------------------------------------------------------------------------
*/

$query_barang_terbaru = mysqli_query($conn,"
    SELECT
        barang.*,
        stok.jumlah_stok
    FROM barang
    LEFT JOIN stok
    ON barang.id_barang = stok.id_barang
    ORDER BY barang.id_barang DESC
    LIMIT 5
");

/*
|--------------------------------------------------------------------------
| STOK MENIPIS
|--------------------------------------------------------------------------
*/

$query_stok_menipis = mysqli_query($conn,"
    SELECT
        barang.nama_barang,
        stok.jumlah_stok,
        stok.stok_minimum
    FROM stok
    JOIN barang
    ON stok.id_barang = barang.id_barang
    WHERE stok.jumlah_stok <= stok.stok_minimum
    ORDER BY stok.jumlah_stok ASC
    LIMIT 5
");

/*
|--------------------------------------------------------------------------
| AKTIVITAS TERBARU
|--------------------------------------------------------------------------
*/

$query_aktivitas = mysqli_query($conn,"
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
    ORDER BY transaksi.id_transaksi DESC
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
        Dashboard Admin
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

    <!-- CSS -->

    <link rel="stylesheet"
          href="../assets/css/admin.css">

</head>

<body>

<div class="dashboard-wrapper">

    <!-- SIDEBAR -->

    <?php include '../templates/sidebar.php'; ?>

    <!-- MAIN CONTENT -->

    <div class="main-content">

        <!-- NAVBAR -->

        <?php include '../templates/navbar.php'; ?>

        <!-- CONTENT -->

        <div class="content">

            <!-- STATISTICS -->

            <div class="stats-grid">

                <!-- TOTAL BARANG -->

                <div class="stats-card">

                    <div class="stats-top">

                        <div>

                            <p>Total Barang</p>

                            <h3>
                                <?= $total_barang; ?>
                            </h3>

                        </div>

                        <div class="stats-icon">

                            <i class="fas fa-box"></i>

                        </div>

                    </div>

                </div>

                <!-- TOTAL STOK -->

                <div class="stats-card">

                    <div class="stats-top">

                        <div>

                            <p>Total Stok</p>

                            <h3>
                                <?= $total_stok; ?>
                            </h3>

                        </div>

                        <div class="stats-icon">

                            <i class="fas fa-warehouse"></i>

                        </div>

                    </div>

                </div>

                <!-- TOTAL TRANSAKSI -->

                <div class="stats-card">

                    <div class="stats-top">

                        <div>

                            <p>Total Transaksi</p>

                            <h3>
                                <?= $total_transaksi; ?>
                            </h3>

                        </div>

                        <div class="stats-icon">

                            <i class="fas fa-cart-shopping"></i>

                        </div>

                    </div>

                </div>

                <!-- TOTAL USER -->

                <div class="stats-card">

                    <div class="stats-top">

                        <div>

                            <p>Total User</p>

                            <h3>
                                <?= $total_user; ?>
                            </h3>

                        </div>

                        <div class="stats-icon">

                            <i class="fas fa-users"></i>

                        </div>

                    </div>

                </div>

                <!-- PENDAPATAN HARI INI -->

                <div class="stats-card">

                    <div class="stats-top">

                        <div>

                            <p>Pendapatan Hari Ini</p>

                            <h3>
                                Rp <?= number_format($total_harian,0,',','.'); ?>
                            </h3>

                        </div>

                        <div class="stats-icon">

                            <i class="fas fa-wallet"></i>

                        </div>

                    </div>

                </div>

                <!-- TOTAL PENDAPATAN -->

                <div class="stats-card">

                    <div class="stats-top">

                        <div>

                            <p>Total Pendapatan</p>

                            <h3>
                                Rp <?= number_format($total_pendapatan,0,',','.'); ?>
                            </h3>

                        </div>

                        <div class="stats-icon">

                            <i class="fas fa-money-bill-trend-up"></i>

                        </div>

                    </div>

                </div>

                <!-- TOTAL PEMBELIAN -->

                <div class="stats-card">

                    <div class="stats-top">

                        <div>

                            <p>Total Pembelian</p>

                            <h3>
                                Rp <?= number_format($total_belanja,0,',','.'); ?>
                            </h3>

                        </div>

                        <div class="stats-icon">

                            <i class="fas fa-truck"></i>

                        </div>

                    </div>

                </div>

            </div>

            <!-- GRID -->

            <div class="dashboard-grid">

                <!-- BARANG TERBARU -->

                <div class="dashboard-card">

                    <h5 class="fw-bold mb-4">
                        Barang Terbaru
                    </h5>

                    <div class="table-responsive">

                        <table class="table align-middle">

                            <thead>

                                <tr>

                                    <th>Barang</th>

                                    <th>Kategori</th>

                                    <th>Stok</th>

                                    <th>Harga Jual</th>

                                    <th>Status</th>

                                </tr>

                            </thead>

                            <tbody>

                            <?php while($barang = mysqli_fetch_assoc($query_barang_terbaru)): ?>

                                <tr>

                                    <td>
                                        <?= $barang['nama_barang']; ?>
                                    </td>

                                    <td>

                                        <span class="badge bg-success-subtle text-success">

                                            <?= $barang['kategori']; ?>

                                        </span>

                                    </td>

                                    <td>

                                        <?= $barang['jumlah_stok']; ?>

                                    </td>

                                    <td>

                                        Rp <?= number_format($barang['harga_jual'],0,',','.'); ?>

                                    </td>

                                    <td>

                                        <?php if($barang['status_barang'] == 'aktif'): ?>

                                            <span class="badge bg-primary">
                                                Aktif
                                            </span>

                                        <?php else: ?>

                                            <span class="badge bg-danger">
                                                Nonaktif
                                            </span>

                                        <?php endif; ?>

                                    </td>

                                </tr>

                            <?php endwhile; ?>

                            </tbody>

                        </table>

                    </div>

                </div>

                <!-- AKTIVITAS -->

                <div class="card shadow-sm border-0 rounded-4">

                    <div class="card-header bg-white border-0 pt-4">

                        <h5 class="fw-bold mb-0">

                            <i class="fas fa-clock-rotate-left text-primary me-2"></i>

                            Aktivitas Terbaru

                        </h5>

                    </div>

                    <div class="card-body">

                    <?php while($aktivitas = mysqli_fetch_assoc($query_aktivitas)): ?>

                        <div class="d-flex align-items-center justify-content-between mb-4">

                            <div class="d-flex align-items-center">

                                <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center"
                                     style="width:50px;height:50px;">

                                    <i class="fas fa-cart-shopping"></i>

                                </div>

                                <div class="ms-3">

                                    <h6 class="mb-1 fw-semibold">

                                        Transaksi #<?= $aktivitas['id_transaksi']; ?>

                                    </h6>

                                    <small class="text-muted">

                                        <?= $aktivitas['nama_pelanggan']; ?>

                                        • Kasir:
                                        <?= $aktivitas['username']; ?>

                                    </small>

                                </div>

                            </div>

                            <div class="text-end">

                                <h6 class="mb-1 text-success fw-bold">

                                    Rp <?= number_format($aktivitas['total_harga'],0,',','.'); ?>

                                </h6>

                                <small class="text-muted">

                                    <?= date('d M Y H:i', strtotime($aktivitas['tanggal'])); ?>

                                </small>

                            </div>

                        </div>

                    <?php endwhile; ?>

                    </div>

                </div>

            </div>

            <!-- STOK MENIPIS -->

            <div class="dashboard-card mt-4">

                <h5 class="fw-bold mb-4 text-danger">

                    <i class="fas fa-triangle-exclamation me-2"></i>

                    Stok Menipis

                </h5>

                <div class="table-responsive">

                    <table class="table align-middle">

                        <thead>

                            <tr>

                                <th>Barang</th>

                                <th>Stok Saat Ini</th>

                                <th>Minimum</th>

                            </tr>

                        </thead>

                        <tbody>

                        <?php while($stok = mysqli_fetch_assoc($query_stok_menipis)): ?>

                            <tr>

                                <td>

                                    <?= $stok['nama_barang']; ?>

                                </td>

                                <td>

                                    <span class="badge bg-danger">

                                        <?= $stok['jumlah_stok']; ?>

                                    </span>

                                </td>

                                <td>

                                    <?= $stok['stok_minimum']; ?>

                                </td>

                            </tr>

                        <?php endwhile; ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<?php include '../templates/footer.php'; ?>

</body>
</html>