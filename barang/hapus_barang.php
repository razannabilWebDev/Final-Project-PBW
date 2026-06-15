<?php

include 'koneksi.php';

$id = $_GET['id'];

$stmt = $conn->prepare("
DELETE FROM barang
WHERE id_barang = ?
");

$stmt->bind_param("i", $id);
$stmt->execute();

$stmt->close();
$conn->close();

// DIUBAH KE index.php
header("Location: index.php");
exit();

?>