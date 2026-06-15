<?php

session_start();

$id_barang = $_POST['id_barang'];
$qty       = $_POST['qty'];

if(!isset($_SESSION['keranjang'])){
    $_SESSION['keranjang'] = [];
}

if(isset($_SESSION['keranjang'][$id_barang])){
    $_SESSION['keranjang'][$id_barang] += $qty;
}else{
    $_SESSION['keranjang'][$id_barang] = $qty;
}

header("Location:tambah.php");