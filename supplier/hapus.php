<?php
session_start();

if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    header("Location: ../login.php");
    exit;
}

if ($_SESSION['role'] !== 'admin') {
    echo "<script>
        alert('Akses Ditolak! Anda tidak memiliki hak akses untuk menghapus data.');
        window.location.href = '../dashboard/kasir.php';
    </script>";
    exit;
}

include '../config/koneksi.php';

if (!isset($_GET['id_supplier']) || empty($_GET['id_supplier'])) {
    header("Location: index.php");
    exit;
}

$id_supplier = mysqli_real_escape_string($conn, $_GET['id_supplier']);

try {
    $query_delete = "DELETE FROM supplier WHERE id_supplier = '$id_supplier'";
    $eksekusi = mysqli_query($conn, $query_delete);

    if ($eksekusi) {
        header("Location: index.php");
        exit;
    } else {
        throw new Exception(mysqli_error($conn));
    }
} catch (mysqli_sql_exception $e) {
    if ($e->getCode() == 1451) {
        echo "<script>
            alert('Gagal Hapus! Supplier ini tidak bisa dihapus karena sudah memiliki riwayat transaksi di tabel pembelian.');
            window.location.href = 'index.php';
        </script>";
    } else {
        echo "<script>
            alert('Gagal menghapus data: " . $e->getMessage() . "');
            window.location.href = 'index.php';
        </script>";
    }
    exit;
}