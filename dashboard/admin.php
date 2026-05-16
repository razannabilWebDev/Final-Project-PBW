<?php
session_start();

// Simulasi session login
$_SESSION['nama'] = 'Admin Warung';
$_SESSION['role'] = 'admin';
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
                            <h3>1,240</h3>
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
                            <h3>845</h3>
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
                            <p>Pendapatan</p>
                            <h3>Rp 24JT</h3>
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
                                    <th>Harga</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr>
                                    <td>Indomie Goreng</td>
                                    <td>Makanan</td>
                                    <td><span class="badge-stock">120 Stok</span></td>
                                    <td>Rp 3.500</td>
                                </tr>

                                <tr>
                                    <td>Teh Botol</td>
                                    <td>Minuman</td>
                                    <td><span class="badge-stock">80 Stok</span></td>
                                    <td>Rp 5.000</td>
                                </tr>

                                <tr>
                                    <td>Beras Premium</td>
                                    <td>Sembako</td>
                                    <td><span class="badge-stock">45 Stok</span></td>
                                    <td>Rp 72.000</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ACTIVITY -->
                <div class="dashboard-card">
                    <h5>Aktivitas Terbaru</h5>

                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-cart-plus"></i>
                        </div>

                        <div>
                            <strong>Transaksi Baru</strong>
                            <p class="mb-0 text-muted small">2 menit lalu</p>
                        </div>
                    </div>

                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-box"></i>
                        </div>

                        <div>
                            <strong>Stok Barang Update</strong>
                            <p class="mb-0 text-muted small">10 menit lalu</p>
                        </div>
                    </div>

                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-user-plus"></i>
                        </div>

                        <div>
                            <strong>Pelanggan Baru</strong>
                            <p class="mb-0 text-muted small">30 menit lalu</p>
                        </div>
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