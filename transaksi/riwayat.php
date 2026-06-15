<?php
session_start();
require_once '../config/koneksi.php';

// Pastikan user sudah login
if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit;
}

// Query mengambil transaksi lengkap dengan JOIN ke user dan pelanggan
$query = "SELECT t.*, u.username, p.nama_pelanggan 
          FROM transaksi t 
          LEFT JOIN user u ON t.id_user = u.id_user 
          LEFT JOIN pelanggan p ON t.id_pelanggan = p.id_pelanggan 
          ORDER BY t.tanggal DESC";
$result = mysqli_query($conn, $query);

function formatRupiah($angka){
    return number_format($angka, 0, ',', '.');
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Transaksi - Groceria</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css"> 
</head>
<body>

    <div class="wrapper">
        <?php include '../templates/sidebar.php'; ?>

        <div class="main-content">
            
            <div class="content-header">
                <h1>Riwayat Transaksi</h1>
                <p>Data seluruh penjualan yang telah tercatat dalam sistem.</p>
            </div>

            <div class="content-body">
                <div class="card">
                    <div class="card-header">
                        Daftar Transaksi
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Kasir</th>
                                        <th>Pelanggan</th>
                                        <th>Total</th>
                                        <th>Bayar</th>
                                        <th>Kembalian</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $no = 1;
                                    if (mysqli_num_rows($result) > 0) {
                                        while($row = mysqli_fetch_assoc($result)): 
                                            // Hitung total akhir setelah diskon
                                            $grand_total = $row['total_harga'] - $row['diskon'];
                                    ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= date('d M Y, H:i', strtotime($row['tanggal'])) ?></td>
                                        <td><?= htmlspecialchars($row['username']) ?></td>
                                        <td><?= $row['nama_pelanggan'] ? htmlspecialchars($row['nama_pelanggan']) : '<span class="badge bg-secondary">Umum</span>' ?></td>
                                        <td class="fw-bold">Rp <?= formatRupiah($grand_total) ?></td>
                                        <td>Rp <?= formatRupiah($row['bayar']) ?></td>
                                        <td>Rp <?= formatRupiah($row['kembalian']) ?></td>
                                        <td>
                                            <a href="cetak_struk.php?id=<?= $row['id_transaksi'] ?>" target="_blank" class="btn btn-sm btn-outline-secondary">
                                                🖨️ Struk
                                            </a>
                                        </td>
                                    </tr>
                                    <?php 
                                        endwhile; 
                                    } else {
                                        echo '<tr><td colspan="8" class="text-center">Belum ada transaksi.</td></tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <?php include '../templates/footer.php'; ?>
            
        </div> </div> </body>
</html>