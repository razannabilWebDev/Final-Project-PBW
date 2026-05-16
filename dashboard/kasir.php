<?php
session_start();

// simulasi login
$_SESSION['nama'] = 'Kasir Warung';
$_SESSION['role'] = 'kasir';

// if($_SESSION['role'] != 'kasir') {
//     header('Location: ../login.php');
//     exit;
// }
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
                            <h3>125</h3>
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
                            <h3>320</h3>
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
                            <h3>Rp 2.5JT</h3>
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
                                <th>No Transaksi</th>
                                <th>Pelanggan</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td>TRX001</td>
                                <td>Budi</td>
                                <td>Rp 120.000</td>
                                <td><span class="badge bg-success">Selesai</span></td>
                            </tr>

                            <tr>
                                <td>TRX002</td>
                                <td>Siti</td>
                                <td>Rp 85.000</td>
                                <td><span class="badge bg-success">Selesai</span></td>
                            </tr>

                            <tr>
                                <td>TRX003</td>
                                <td>Andi</td>
                                <td>Rp 45.000</td>
                                <td><span class="badge bg-warning text-dark">Pending</span></td>
                            </tr>
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