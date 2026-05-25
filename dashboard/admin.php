<?php
session_start();
require '../config/koneksi.php';
require '../config/session.php';

cek_login_admin();

$total_barang = mysqli_num_rows(
    mysqli_query($conn,"SELECT * FROM barang")
);

$total_supplier = mysqli_num_rows(
    mysqli_query($conn,"SELECT * FROM supplier")
);

$total_pelanggan = mysqli_num_rows(
    mysqli_query($conn,"SELECT * FROM pelanggan")
);

$total_transaksi = mysqli_num_rows(
    mysqli_query($conn,"SELECT * FROM transaksi")
);

function pendapatan_harian($conn) {
    $query = "
        SELECT SUM(detail_transaksi.subtotal) as total
        FROM detail_transaksi
        JOIN transaksi
        ON detail_transaksi.id_transaksi = transaksi.id_transaksi
        WHERE transaksi.tanggal = CURDATE()
    ";
    $result = mysqli_query($conn, $query);
    $data = mysqli_fetch_assoc($result);
    return $data['total'];
}


function pendapatan_total($conn) {
    $query = "SELECT SUM(subtotal) as total_pendapatan FROM detail_transaksi";
    $result = mysqli_query($conn, $query);
    $data = mysqli_fetch_assoc($result);
    return $data['total_pendapatan'];
}

$total_harian = pendapatan_harian($conn);
$total_pendapatan = pendapatan_total($conn);

$query_barang_terbaru = mysqli_query($conn, "
    SELECT *
    FROM barang
    ORDER BY id_barang DESC
    LIMIT 5
");

$query_aktivitas = mysqli_query($conn, "
    SELECT 
        transaksi.id_transaksi,
        transaksi.tanggal,
        transaksi.total_harga,
        pelanggan.nama_pelanggan
    FROM transaksi
    JOIN pelanggan
    ON transaksi.id_pelanggan = pelanggan.id_pelanggan
    ORDER BY transaksi.id_transaksi DESC
    LIMIT 5
");
 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Warung Kelontong</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/admin.css">
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

                <div class="stats-card">
                    <div class="stats-top">
                        <div>
                            <p>Total Barang</p>
                            <h3><?php echo $total_barang; ?></h3>
                        </div>

                        <div class="stats-icon">
                            <i class="fas fa-box"></i>
                        </div>
                    </div>
                </div>

                <div class="stats-card">
                    <div class="stats-top">
                        <div>
                            <p>Total Transaksi</p>
                            <h3><?php echo $total_transaksi; ?></h3>
                        </div>

                        <div class="stats-icon">
                            <i class="fas fa-cart-shopping"></i>
                        </div>
                    </div>
                </div>

                <div class="stats-card">
                    <div class="stats-top">
                        <div>
                            <p>Pelanggan</p>
                            <h3><?php echo $total_pelanggan; ?></h3>
                        </div>

                        <div class="stats-icon">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>

                <div class="stats-card">
                    <div class="stats-top">
                        <div>
                            <p>Pendapatan Hari Ini</p>
                            <h3>Rp <?php echo number_format($total_harian, 0, ',', '.'); ?></h3>
                        </div>

                        <div class="stats-icon">
                            <i class="fas fa-wallet"></i>
                        </div>
                    </div>
                </div>

                <div class="stats-card">
                    <div class="stats-top">
                        <div>
                            <p>Pendapatan Total</p>
                            <h3>Rp <?php echo number_format($total_pendapatan, 0, ',', '.'); ?></h3>
                        </div>

                        <div class="stats-icon">
                            <i class="fas fa-wallet"></i>
                        </div>
                    </div>
                </div>

            </div>

            <!-- DASHBOARD CONTENT -->
            <div class="dashboard-grid">

                <!-- TABLE -->
                <div class="dashboard-card">
                    <h5>Barang Terbaru</h5>

                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Nama Barang</th>
                                    <th>Kategori</th>
                                    <th>Stok</th>
                                    <th>Harga Beli</th>
                                    <th>Harga Jual</th>
                                    <th>Tanggal Masuk</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php while($barang = mysqli_fetch_assoc($query_barang_terbaru)) : ?>
                                <tr>
                                    <td><?= $barang['nama_barang']; ?></td>
                                    <td>
                                        <span class="badge bg-success-subtle text-success">
                                            <?= $barang['kategori']; ?>
                                        </span>
                                    </td>
                                    <td><?= $barang['stok']; ?></td>
                                    <td>Rp <?= number_format($barang['harga_beli'], 0, ',', '.'); ?></td>
                                    <td>Rp <?= number_format($barang['harga_jual'], 0, ',', '.'); ?></td>
                                    <td>
                                        <?= date('d M Y', strtotime($barang['tanggal_masuk'])) ?>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ACTIVITY -->
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-header bg-white border-0 pt-4">
                        <h5 class="fw-bold mb-0">
                            <i class="fas fa-clock-rotate-left text-primary me-2"></i>
                            Aktivitas Terbaru
                        </h5>
                    </div>

                    <div class="card-body">

                        <?php while($aktivitas = mysqli_fetch_assoc($query_aktivitas)) : ?>

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
                                    </small>

                                </div>

                            </div>

                            <div class="text-end">

                                <h6 class="mb-1 text-success fw-bold">
                                    Rp <?= number_format($aktivitas['total_harga'],0,',','.') ?>
                                </h6>

                                <small class="text-muted">
                                    <?= date('d M Y', strtotime($aktivitas['tanggal'])) ?>
                                </small>

                            </div>

                        </div>

                        <?php endwhile; ?>

                    </div>

                </div>
            </div>

        </div>

        <!-- FOOTER -->
        <?php include '../templates/footer.php'; ?>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>