<?php
session_start();
require '../config/koneksi.php';
require '../config/session.php';

cek_login_kasir();

$total_pelanggan = mysqli_num_rows(
    mysqli_query($conn,"SELECT * FROM pelanggan")
);

function Transaksi_harian($conn) {
    $query = "
        SELECT COUNT(*) as total
        FROM transaksi
        WHERE tanggal = CURDATE()
    ";
    $result = mysqli_query($conn, $query);
    $data = mysqli_fetch_assoc($result);
    return $data['total'];
}



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

$query_transaksi_terbaru = mysqli_query($conn, "
    SELECT *
    FROM transaksi
    ORDER BY id_transaksi DESC
    LIMIT 5
");

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Kasir</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/kasir.css">
</head>
<body>

<div class="dashboard-wrapper">

    <?php include '../templates/sidebar.php'; ?>

    <div class="main-content">

        <?php include '../templates/navbar.php'; ?>

        <div class="content">

            <div class="welcome-card">
                <h2>Selamat Datang, Kasir 👋</h2>
                <p>Kelola transaksi dan pelanggan dengan cepat dan efisien.</p>
            </div>

            <div class="stats-grid">

                <div class="stats-card">
                    <div class="stats-top">
                        <div>
                            <p>Transaksi Hari Ini</p>
                            <h3><?php echo Transaksi_harian($conn); ?></h3>
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
                            <h3>Rp <?php echo number_format(pendapatan_harian($conn), 0, ',', '.'); ?></h3>
                        </div>

                        <div class="stats-icon">
                            <i class="fas fa-wallet"></i>
                        </div>
                    </div>
                </div>

            </div>

            <div class="quick-action-grid">

                <a href="../transaksi/index.php" class="quick-action">
                    <i class="fas fa-cash-register"></i>
                    <h5>Transaksi Baru</h5>
                    <p class="text-muted mb-0">Mulai transaksi pelanggan</p>
                </a>

                <a href="../pelanggan/index.php" class="quick-action">
                    <i class="fas fa-user-plus"></i>
                    <h5>Tambah Pelanggan</h5>
                    <p class="text-muted mb-0">Input data pelanggan baru</p>
                </a>

            </div>

            <div class="transaction-card">
                <h5>Transaksi Terbaru</h5>

                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Id Transaksi</th>
                                <th>Pelanggan</th>
                                <th>Total</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>

                        <tbody>
                                <?php while($transaksi = mysqli_fetch_assoc($query_transaksi_terbaru)) : ?>
                                <tr>
                                    <td><?= $transaksi['id_transaksi']; ?></td>
                                    <td><?= $transaksi['id_pelanggan']; ?></td>
                                    <td>Rp <?= number_format($transaksi['total_harga'], 0, ',', '.'); ?></td>
                                    <td>
                                        <?= date('d M Y', strtotime($transaksi['tanggal'])) ?>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                    </table>
                </div>

            </div>

        </div>
        <?php include '../templates/footer.php'; ?>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>