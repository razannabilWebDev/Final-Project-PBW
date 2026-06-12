<?php
session_start();
include '../config/koneksi.php';
require '../config/session.php';

cek_login_admin();

$tanggal_awal = $_GET['tanggal_awal'] ?? '';
$tanggal_akhir = $_GET['tanggal_akhir'] ?? '';

$where = "";

if($tanggal_awal && $tanggal_akhir){
    $where = "WHERE DATE(transaksi.tanggal) 
              BETWEEN '$tanggal_awal' 
              AND '$tanggal_akhir'";
}

$query = mysqli_query($conn, "
    SELECT
        transaksi.id_transaksi,
        transaksi.tanggal,
        pelanggan.nama_pelanggan,
        transaksi.total_harga,
        transaksi.bayar,
        transaksi.kembalian
    FROM transaksi
    JOIN pelanggan
    ON transaksi.id_pelanggan = pelanggan.id_pelanggan
    $where
    ORDER BY transaksi.id_transaksi DESC
");


// =====================================
// TOTAL PENDAPATAN
// =====================================

$total_penjualan_query = mysqli_query($conn, "
    SELECT SUM(detail_transaksi.subtotal) as total_penjualan
    FROM detail_transaksi
    JOIN transaksi
    ON detail_transaksi.id_transaksi = transaksi.id_transaksi
    $where
");

$total_penjualan = mysqli_fetch_assoc($total_penjualan_query);


// =====================================
// TOTAL MODAL
// =====================================

$total_modal_query = mysqli_query($conn, "
    SELECT SUM(
        detail_transaksi.jumlah * barang.harga_beli
    ) as total_modal

    FROM detail_transaksi

    JOIN transaksi
    ON detail_transaksi.id_transaksi = transaksi.id_transaksi

    JOIN barang
    ON detail_transaksi.id_barang = barang.id_barang

    $where
");

$total_modal = mysqli_fetch_assoc($total_modal_query);


// =====================================
// HITUNG LABA / RUGI
// =====================================

$penjualan = $total_penjualan['total_penjualan'] ?? 0;
$modal = $total_modal['total_modal'] ?? 0;

$laba_bersih = $penjualan - $modal;

$status_keuangan = ($laba_bersih >= 0) ? "UNTUNG" : "RUGI";

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan</title>

    <style>

        body{
            font-family: Arial, sans-serif;
            padding: 30px;
            color: #333;
        }

        .header{
            text-align: center;
            margin-bottom: 30px;
        }

        .header h2{
            margin-bottom: 5px;
        }

        .header p{
            margin: 0;
            color: #666;
        }

        .info-laporan{
            margin-bottom: 20px;
        }

        .info-laporan table{
            width: 400px;
        }

        .info-laporan td{
            padding: 4px 0;
        }

        table.data{
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table.data th,
        table.data td{
            border: 1px solid #ccc;
            padding: 10px;
            font-size: 14px;
        }

        table.data th{
            background: #f3f3f3;
        }

        .summary{
            margin-top: 30px;
            width: 400px;
            margin-left: auto;
        }

        .summary table{
            width: 100%;
            border-collapse: collapse;
        }

        .summary td{
            padding: 10px;
            border: 1px solid #ccc;
        }

        .summary .label{
            font-weight: bold;
            background: #f7f7f7;
        }

        .untung{
            color: green;
            font-weight: bold;
        }

        .rugi{
            color: red;
            font-weight: bold;
        }

        @media print{
            body{
                padding: 10px;
            }
        }

    </style>
</head>
<body>

<div class="header">
    <h2>Laporan Penjualan Warung Kelontong</h2>

    <p>
        Dicetak pada <?= date('d M Y H:i') ?>
    </p>

    <?php if($tanggal_awal && $tanggal_akhir): ?>
        <p>
            Periode :
            <?= date('d M Y', strtotime($tanggal_awal)) ?>
            -
            <?= date('d M Y', strtotime($tanggal_akhir)) ?>
        </p>
    <?php endif; ?>
</div>


<div class="info-laporan">

    <table>
        <tr>
            <td>Total Transaksi</td>
            <td>:</td>
            <td><?= mysqli_num_rows($query); ?></td>
        </tr>

        <tr>
            <td>Total Pendapatan</td>
            <td>:</td>
            <td>
                Rp <?= number_format($penjualan,0,',','.') ?>
            </td>
        </tr>

        <tr>
            <td>Total Modal</td>
            <td>:</td>
            <td>
                Rp <?= number_format($modal,0,',','.') ?>
            </td>
        </tr>
    </table>

</div>


<table class="data">

    <thead>
        <tr>
            <th>No</th>
            <th>ID Transaksi</th>
            <th>Tanggal</th>
            <th>Pelanggan</th>
            <th>Total Belanja</th>
            <th>Bayar</th>
            <th>Kembalian</th>
        </tr>
    </thead>

    <tbody>

        <?php
        $no = 1;

        while($row = mysqli_fetch_assoc($query)) :
        ?>

        <tr>
            <td><?= $no++ ?></td>

            <td>
                TRX<?= $row['id_transaksi'] ?>
            </td>

            <td>
                <?= date('d M Y H:i', strtotime($row['tanggal'])) ?>
            </td>

            <td>
                <?= $row['nama_pelanggan'] ?>
            </td>

            <td>
                Rp <?= number_format($row['total_harga'],0,',','.') ?>
            </td>

            <td>
                Rp <?= number_format($row['bayar'],0,',','.') ?>
            </td>

            <td>
                Rp <?= number_format($row['kembalian'],0,',','.') ?>
            </td>
        </tr>

        <?php endwhile; ?>

    </tbody>

</table>


<div class="summary">

    <table>

        <tr>
            <td class="label">Total Pendapatan</td>

            <td>
                Rp <?= number_format($penjualan,0,',','.') ?>
            </td>
        </tr>

        <tr>
            <td class="label">Total Modal</td>

            <td>
                Rp <?= number_format($modal,0,',','.') ?>
            </td>
        </tr>

        <tr>
            <td class="label">
                <?= $status_keuangan ?>
            </td>

            <td class="<?= ($laba_bersih >= 0) ? 'untung' : 'rugi' ?>">
                Rp <?= number_format(abs($laba_bersih),0,',','.') ?>
            </td>
        </tr>

    </table>

</div>

<script>
    window.print();
</script>

</body>
</html>