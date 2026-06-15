<?php
require '../config/koneksi.php';

$id = $_GET['id'];

$trx = mysqli_query($conn,"
    SELECT t.*,
           p.nama_pelanggan
    FROM transaksi t
    JOIN pelanggan p
    ON t.id_pelanggan=p.id_pelanggan
    WHERE t.id_transaksi='$id'
");

$data = mysqli_fetch_assoc($trx);

$detail = mysqli_query($conn,"
    SELECT dt.*, b.nama_barang
    FROM detail_transaksi dt
    JOIN barang b
    ON dt.id_barang=b.id_barang
    WHERE dt.id_transaksi='$id'
");
?>

<!DOCTYPE html>
<html>
<head>

    <title>Cetak Struk</title>

    <style>

        #struk {

            max-width: 700px;
            margin: auto;
            padding: 20px;
            background: white;
        }

        @media print {

            .btn {
                display: none;
            }

        }

        </style>

</head>

<body onload="window.print()">

<div class="container mt-4" id="struk">

    <h3 class="text-center">
        TOKO KELONTONG
    </h3>

    <hr>

    <p>
        Tanggal :
        <?= $data['tanggal'] ?>
    </p>

    <p>
        Pelanggan :
        <?= $data['nama_pelanggan'] ?>
    </p>

    <table class="table table-bordered">

        <thead>
            <tr>
                <th>Barang</th>
                <th>Qty</th>
                <th>Subtotal</th>
            </tr>
        </thead>

        <tbody>

            <?php while($d = mysqli_fetch_assoc($detail)) : ?>

            <tr>

                <td><?= $d['nama_barang'] ?></td>

                <td><?= $d['jumlah'] ?></td>

                <td>
                    Rp<?= number_format($d['subtotal']) ?>
                </td>

            </tr>

            <?php endwhile; ?>

        </tbody>

    </table>

    <hr>

    <p>
        Total :
        Rp<?= number_format($data['total_harga']) ?>
    </p>

    <p>
        Diskon :
        Rp<?= number_format($data['diskon']) ?>
    </p>

    <p>
        Total Bayar :
        Rp<?= number_format($data['total_bayar']) ?>
    </p>

    <p>
        Bayar :
        Rp<?= number_format($data['bayar']) ?>
    </p>

    <p>
        Kembalian :
        Rp<?= number_format($data['kembalian']) ?>
    </p>

    <p>
        Poin Didapat :
        <?= $data['poin_didapat'] ?>
    </p>

</div>
<div class="text-center mt-3">

    <button
        class="btn btn-primary"
        onclick="downloadPDF()">

        Export PDF

    </button>

    <button
        class="btn btn-success"
        onclick="window.print()">

        Cetak Struk

    </button>

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<script>

function downloadPDF() {

    const element = document.getElementById('struk');

    html2pdf().set({

        margin: 10,

        filename:
            'struk-<?= $data['id_transaksi'] ?>.pdf',

        image: {
            type: 'jpeg',
            quality: 1
        },

        html2canvas: {
            scale: 2
        },

        jsPDF: {
            unit: 'mm',
            format: 'a5',
            orientation: 'portrait'
        }

    }).from(element).save();
}

</script>
</body>
</html>