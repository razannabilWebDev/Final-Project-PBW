<?php
require_once '../config/koneksi.php';

// Ambil ID Transaksi dari URL
$id_transaksi = isset($_GET['id']) ? $_GET['id'] : 0;

if (!$id_transaksi) {
    die("ID Transaksi tidak valid.");
}

// 1. Ambil data Transaksi Utama, data Pelanggan (jika ada), dan Username Kasir
$query_trans = "SELECT t.*, p.nama_pelanggan, p.poin_member, u.username 
                FROM transaksi t 
                LEFT JOIN pelanggan p ON t.id_pelanggan = p.id_pelanggan 
                LEFT JOIN user u ON t.id_user = u.id_user
                WHERE t.id_transaksi = ?";
$stmt = $conn->prepare($query_trans);
$stmt->bind_param("i", $id_transaksi);
$stmt->execute();
$result_trans = $stmt->get_result();

if ($result_trans->num_rows === 0) {
    die("Transaksi tidak ditemukan.");
}

$trans = $result_trans->fetch_assoc();

// 2. Ambil detail barang yang dibeli
$query_detail = "SELECT dt.*, b.nama_barang 
                 FROM detail_transaksi dt 
                 JOIN barang b ON dt.id_barang = b.id_barang 
                 WHERE dt.id_transaksi = ?";
$stmt_detail = $conn->prepare($query_detail);
$stmt_detail->bind_param("i", $id_transaksi);
$stmt_detail->execute();
$result_detail = $stmt_detail->get_result();

// Fungsi format rupiah
function formatRupiah($angka){
    return number_format($angka, 0, ',', '.');
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Transaksi #<?= $id_transaksi ?></title>
    <style>
        /* Styling khusus untuk menyerupai printer thermal */
        @page {
            margin: 0;
        }
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 14px;
            background-color: #f0f0f0;
            color: #000;
        }
        .struk-container {
            width: 80mm; /* Standar ukuran thermal printer */
            margin: 20px auto;
            background: #fff;
            padding: 15px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .bold { font-weight: bold; }
        .dashed-line {
            border-top: 1px dashed #000;
            margin: 10px 0;
        }
        
        table { width: 100%; border-collapse: collapse; }
        td { vertical-align: top; padding: 2px 0; }
        
        .item-name { display: block; font-size: 13px; }
        .item-qty-price { font-size: 13px; color: #333; }

        /* Menghilangkan shadow dan background abu-abu saat diprint ke kertas */
        @media print {
            body { background-color: #fff; }
            .struk-container { margin: 0; box-shadow: none; padding: 0; width: 100%; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>

    <div class="struk-container">
        <div class="text-center">
            <h2 style="margin: 0; font-size: 18px;">Groceria</h2>
            <p style="margin: 5px 0 0; font-size: 12px;">
                Jl. Kebun Kelapa Sawit No. 123<br>
                Telp: 0812-3456-7890
            </p>
        </div>

        <div class="dashed-line"></div>

        <table style="font-size: 12px;">
            <tr>
                <td>No.</td>
                <td>: TRC-<?= str_pad($id_transaksi, 5, "0", STR_PAD_LEFT) ?></td>
            </tr>
            <tr>
                <td>Tgl.</td>
                <td>: <?= date('d/m/Y H:i', strtotime($trans['tanggal'] ?? date('Y-m-d H:i:s'))) ?></td>
            </tr>
            <tr>
                <td>Kasir</td>
                <td>: <?= $trans['username'] ?></td>
            </tr>
        </table>

        <div class="dashed-line"></div>

        <table>
            <?php while($item = $result_detail->fetch_assoc()): ?>
            <tr>
                <td colspan="3"><span class="item-name"><?= $item['nama_barang'] ?></span></td>
            </tr>
            <tr>
                <td class="item-qty-price" style="width: 50%;"><?= $item['jumlah'] ?> x <?= formatRupiah($item['harga_jual']) ?></td>
                <td class="text-right bold" style="width: 50%;">Rp <?= formatRupiah($item['subtotal']) ?></td>
            </tr>
            <?php endwhile; ?>
        </table>

        <div class="dashed-line"></div>

        <table>
            <tr>
                <td>Total Belanja</td>
                <td class="text-right">Rp <?= formatRupiah($trans['total_harga']) ?></td>
            </tr>
            
            <?php if($trans['diskon'] > 0): ?>
            <tr>
                <td>Diskon (Tukar Poin)</td>
                <td class="text-right" style="color: red;">- Rp <?= formatRupiah($trans['diskon']) ?></td>
            </tr>
            <?php endif; ?>
            
            <?php $grand_total = $trans['total_harga'] - $trans['diskon']; ?>
            <tr class="bold" style="font-size: 16px;">
                <td style="padding-top: 5px;">GRAND TOTAL</td>
                <td class="text-right" style="padding-top: 5px;">Rp <?= formatRupiah($grand_total) ?></td>
            </tr>
            <tr>
                <td>Tunai</td>
                <td class="text-right">Rp <?= formatRupiah($trans['bayar']) ?></td>
            </tr>
            <tr>
                <td>Kembalian</td>
                <td class="text-right">Rp <?= formatRupiah($trans['kembalian']) ?></td>
            </tr>
        </table>

        <div class="dashed-line"></div>

        <?php if($trans['id_pelanggan']): ?>
        <div class="text-center" style="font-size: 12px; margin-bottom: 10px;">
            <strong>MEMBER: <?= strtoupper($trans['nama_pelanggan']) ?></strong><br>
            Poin didapat: +<?= $trans['poin_didapat'] ?> Poin
        </div>
        <div class="dashed-line"></div>
        <?php endif; ?>

        <div class="text-center" style="font-size: 13px; margin-top: 15px;">
            Terima Kasih Atas Kunjungan Anda<br>
            <span style="font-size: 11px;">Barang yang sudah dibeli tidak dapat ditukar/dikembalikan</span>
        </div>

    </div>

    <div class="text-center no-print" style="margin-bottom: 30px;">
        <button onclick="window.print()" style="padding: 10px 20px; font-size: 16px; cursor: pointer; background: #52634f; color: white; border: none; border-radius: 5px;">
            🖨️ Cetak Ulang Struk
        </button>
        <button onclick="window.close()" style="padding: 10px 20px; font-size: 16px; cursor: pointer; background: #dc3545; color: white; border: none; border-radius: 5px; margin-left: 10px;">
            Tutup Tab
        </button>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>