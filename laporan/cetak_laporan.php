<?php
include '../config/koneksi.php';
require '../config/session.php';
cek_login_admin();

$tanggal_awal = $_GET['tanggal_awal'] ?? '';
$tanggal_akhir = $_GET['tanggal_akhir'] ?? '';

$where = "";

if($tanggal_awal && $tanggal_akhir){
    $where = "WHERE transaksi.tanggal BETWEEN '$tanggal_awal' AND '$tanggal_akhir'";
}

$query = mysqli_query($conn, "
    SELECT
        transaksi.id_transaksi,
        transaksi.tanggal,
        pelanggan.nama_pelanggan,
        transaksi.total_harga
    FROM transaksi
    JOIN pelanggan
    ON transaksi.id_pelanggan = pelanggan.id_pelanggan
    $where
    ORDER BY transaksi.id_transaksi DESC
");

$total_query = mysqli_query($conn, "
    SELECT SUM(total_harga) as total
    FROM transaksi
    $where
");

$total = mysqli_fetch_assoc($total_query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan</title>

    <style>
        body{
            font-family:Arial,sans-serif;
            padding:30px;
        }

        h2{
            text-align:center;
            margin-bottom:5px;
        }

        p{
            text-align:center;
            margin-top:0;
            color:#555;
        }

        table{
            width:100%;
            border-collapse:collapse;
            margin-top:30px;
        }

        table th,
        table td{
            border:1px solid #ccc;
            padding:12px;
            text-align:left;
        }

        table th{
            background:#f2f2f2;
        }

        .total{
            margin-top:20px;
            text-align:right;
            font-size:18px;
            font-weight:bold;
        }
    </style>
</head>
<body>

<h2>Laporan Penjualan Warung Kelontong</h2>
<p>Dicetak pada <?= date('d M Y') ?></p>

<table>

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
        while($row = mysqli_fetch_assoc($query)) :
        ?>

        <tr>
            <td><?= $no++ ?></td>
            <td>TRX<?= $row['id_transaksi'] ?></td>
            <td><?= date('d M Y', strtotime($row['tanggal'])) ?></td>
            <td><?= $row['nama_pelanggan'] ?></td>
            <td>
                Rp <?= number_format($row['total_harga'],0,',','.') ?>
            </td>
        </tr>

        <?php endwhile; ?>

    </tbody>

</table>

<div class="total">
    Total Pendapatan :
    Rp <?= number_format($total['total'] ?? 0,0,',','.') ?>
</div>

<script>
    window.print();
</script>

</body>
</html>