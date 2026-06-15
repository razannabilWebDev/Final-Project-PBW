<?php
session_start();

require '../config/koneksi.php';

$id_pelanggan = $_POST['id_pelanggan'];
$id_user = $_SESSION['id_user'];
$bayar = $_POST['bayar'];

$total = 0;

foreach($_SESSION['keranjang'] as $item){

    $total += $item['subtotal'];
}

$qPelanggan = mysqli_query($conn,"
    SELECT *
    FROM pelanggan
    WHERE id_pelanggan='$id_pelanggan'
");

$pelanggan = mysqli_fetch_assoc($qPelanggan);

$diskon = 0;

if($pelanggan['poin_member'] > 100){

    $diskon = $total * 0.10;
}

$total_bayar = $total - $diskon;
$kembalian = $bayar - $total_bayar;

if($bayar < $total_bayar){

    die("
    <script>
        alert('Uang pembayaran kurang');
        window.location='index.php';
    </script>");
}

$poin_didapat = 0;

if($total_bayar >= 100000 && $id_pelanggan != 1){

    $poin_didapat = 10;
}

mysqli_begin_transaction($conn);

try{

    mysqli_query($conn,"
        INSERT INTO transaksi(
        tanggal,
        id_pelanggan,
        id_user,
        total_harga,
        diskon,
        total_bayar,
        poin_didapat,
        bayar,
        kembalian
    )
    VALUES(
        NOW(),
        '$id_pelanggan',
        '$id_user',
        '$total',
        '$diskon',
        '$total_bayar',
        '$poin_didapat',
        '$bayar',
        '$kembalian'
    )
    ");

    $id_transaksi = mysqli_insert_id($conn);

    foreach($_SESSION['keranjang'] as $item){

        mysqli_query($conn,"
            INSERT INTO detail_transaksi(
                id_transaksi,
                id_barang,
                jumlah,
                harga_jual,
                subtotal
            ) VALUES(
                '$id_transaksi',
                '{$item['id_barang']}',
                '{$item['qty']}',
                '{$item['harga']}',
                '{$item['subtotal']}'
            )
        ");

        mysqli_query($conn,"
            UPDATE stok
            SET jumlah_stok = jumlah_stok - {$item['qty']},
                terakhir_diupdate = NOW()
            WHERE id_barang = {$item['id_barang']}
        ");
    }

    if($poin_didapat > 0){

        mysqli_query($conn,"
            UPDATE pelanggan
            SET poin_member = poin_member + $poin_didapat
            WHERE id_pelanggan='$id_pelanggan'
        ");
    }

    mysqli_commit($conn);

    unset($_SESSION['keranjang']);

    header("Location:cetak.php?id=$id_transaksi");

}catch(Exception $e){

    mysqli_rollback($conn);

    echo $e->getMessage();
}