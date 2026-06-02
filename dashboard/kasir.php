<?php
session_start();

require '../config/koneksi.php';
require '../config/session.php';

cek_login_kasir();

/*
|--------------------------------------------------------------------------
| DATA USER LOGIN
|--------------------------------------------------------------------------
*/

$id_user = $_SESSION['id_user'];

/*
|--------------------------------------------------------------------------
| TOTAL PELANGGAN
|--------------------------------------------------------------------------
*/

$total_pelanggan = mysqli_num_rows(
    mysqli_query($conn,"SELECT * FROM pelanggan")
);

/*
|--------------------------------------------------------------------------
| TRANSAKSI HARI INI
|--------------------------------------------------------------------------
*/

function transaksi_harian($conn, $id_user){

    $query = mysqli_query($conn,"
        SELECT COUNT(*) AS total
        FROM transaksi
        WHERE DATE(tanggal) = CURDATE()
        AND id_user = '$id_user'
    ");

    $data = mysqli_fetch_assoc($query);

    return $data['total'] ?? 0;
}

/*
|--------------------------------------------------------------------------
| PENDAPATAN HARI INI
|--------------------------------------------------------------------------
*/

function pendapatan_harian($conn, $id_user){

    $query = mysqli_query($conn,"
        SELECT SUM(detail_transaksi.subtotal) AS total
        FROM detail_transaksi
        LEFT JOIN transaksi
        ON detail_transaksi.id_transaksi = transaksi.id_transaksi
        WHERE DATE(transaksi.tanggal) = CURDATE()
        AND transaksi.id_user = '$id_user'
    ");

    $data = mysqli_fetch_assoc($query);

    return $data['total'] ?? 0;
}

/*
|--------------------------------------------------------------------------
| TOTAL TRANSAKSI KASIR
|--------------------------------------------------------------------------
*/

function total_transaksi_kasir($conn, $id_user){

    $query = mysqli_query($conn,"
        SELECT COUNT(*) AS total
        FROM transaksi
        WHERE id_user = '$id_user'
    ");

    $data = mysqli_fetch_assoc($query);

    return $data['total'] ?? 0;
}

/*
|--------------------------------------------------------------------------
| TRANSAKSI TERBARU
|--------------------------------------------------------------------------
*/

$query_transaksi_terbaru = mysqli_query($conn,"
    SELECT
        transaksi.*,
        pelanggan.nama_pelanggan
    FROM transaksi
    LEFT JOIN pelanggan
    ON transaksi.id_pelanggan = pelanggan.id_pelanggan
    WHERE transaksi.id_user = '$id_user'
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

    <title>Dashboard Kasir</title>

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
          href="../assets/css/kasir.css">

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

            <!-- WELCOME -->

            <div class="welcome-card">

                <h2>
                    Selamat Datang,
                    <?= $_SESSION['username']; ?> 👋
                </h2>

                <p>
                    Kelola transaksi pelanggan dengan cepat dan efisien.
                </p>

            </div>

            <!-- STATISTICS -->

            <div class="stats-grid">

                <!-- TRANSAKSI HARI INI -->

                <div class="stats-card">

                    <div class="stats-top">

                        <div>

                            <p>Transaksi Hari Ini</p>

                            <h3>
                                <?= transaksi_harian($conn, $id_user); ?>
                            </h3>

                        </div>

                        <div class="stats-icon">

                            <i class="fas fa-cart-shopping"></i>

                        </div>

                    </div>

                </div>

                <!-- TOTAL PELANGGAN -->

                <div class="stats-card">

                    <div class="stats-top">

                        <div>

                            <p>Total Pelanggan</p>

                            <h3>
                                <?= $total_pelanggan; ?>
                            </h3>

                        </div>

                        <div class="stats-icon">

                            <i class="fas fa-users"></i>

                        </div>

                    </div>

                </div>

                <!-- PENDAPATAN -->

                <div class="stats-card">

                    <div class="stats-top">

                        <div>

                            <p>Pendapatan Hari Ini</p>

                            <h3>

                                Rp <?= number_format(
                                    pendapatan_harian($conn, $id_user),
                                    0,
                                    ',',
                                    '.'
                                ); ?>

                            </h3>

                        </div>

                        <div class="stats-icon">

                            <i class="fas fa-wallet"></i>

                        </div>

                    </div>

                </div>

                <!-- TOTAL TRANSAKSI -->

                <div class="stats-card">

                    <div class="stats-top">

                        <div>

                            <p>Total Semua Transaksi</p>

                            <h3>
                                <?= total_transaksi_kasir($conn, $id_user); ?>
                            </h3>

                        </div>

                        <div class="stats-icon">

                            <i class="fas fa-receipt"></i>

                        </div>

                    </div>

                </div>

            </div>

            <!-- QUICK ACTION -->

            <div class="quick-action-grid">

                <!-- TRANSAKSI -->

                <a href="../transaksi/index.php"
                   class="quick-action">

                    <i class="fas fa-cash-register"></i>

                    <h5>Transaksi Baru</h5>

                    <p class="text-muted mb-0">

                        Mulai transaksi pelanggan

                    </p>

                </a>

                <!-- PELANGGAN -->

                <a href="../pelanggan/index.php"
                   class="quick-action">

                    <i class="fas fa-user-plus"></i>

                    <h5>Tambah Pelanggan</h5>

                    <p class="text-muted mb-0">

                        Input pelanggan baru

                    </p>

                </a>

            </div>

            <!-- TRANSAKSI TERBARU -->

            <div class="transaction-card">

                <div class="d-flex justify-content-between align-items-center mb-4">

                    <h5 class="fw-bold mb-0">

                        <i class="fas fa-clock-rotate-left text-primary me-2"></i>

                        Transaksi Terbaru

                    </h5>

                    <a href="../transaksi/riwayat.php"
                       class="btn btn-primary btn-sm">

                        Lihat Semua

                    </a>

                </div>

                <div class="table-responsive">

                    <table class="table align-middle">

                        <thead>

                            <tr>

                                <th>ID</th>

                                <th>Pelanggan</th>

                                <th>Total</th>

                                <th>Bayar</th>

                                <th>Kembalian</th>

                                <th>Tanggal</th>

                            </tr>

                        </thead>

                        <tbody>

                        <?php while($transaksi = mysqli_fetch_assoc($query_transaksi_terbaru)) : ?>

                            <tr>

                                <td>

                                    #<?= $transaksi['id_transaksi']; ?>

                                </td>

                                <td>

                                    <?= $transaksi['nama_pelanggan']; ?>

                                </td>

                                <td>

                                    <span class="fw-semibold text-success">

                                        Rp <?= number_format(
                                            $transaksi['total_harga'],
                                            0,
                                            ',',
                                            '.'
                                        ); ?>

                                    </span>

                                </td>

                                <td>

                                    Rp <?= number_format(
                                        $transaksi['bayar'],
                                        0,
                                        ',',
                                        '.'
                                    ); ?>

                                </td>

                                <td>

                                    Rp <?= number_format(
                                        $transaksi['kembalian'],
                                        0,
                                        ',',
                                        '.'
                                    ); ?>

                                </td>

                                <td>

                                    <?= date(
                                        'd M Y H:i',
                                        strtotime($transaksi['tanggal'])
                                    ); ?>

                                </td>

                            </tr>

                        <?php endwhile; ?>

                        </tbody>

                    </table>

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