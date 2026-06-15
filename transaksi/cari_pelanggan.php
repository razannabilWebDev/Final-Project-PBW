<?php
require_once '../config/koneksi.php';
header('Content-Type: application/json');

$q = isset($_GET['q']) ? $_GET['q'] : '';

$query = "SELECT id_pelanggan, nama_pelanggan, no_hp, poin_member 
          FROM pelanggan 
          WHERE no_hp LIKE ? OR nama_pelanggan LIKE ? LIMIT 1";

$stmt = $conn->prepare($query);
$search = "%$q%";
$stmt->bind_param("ss", $search, $search);
$stmt->execute();
$result = $stmt->get_result();

if($row = $result->fetch_assoc()) {
    echo json_encode($row);
} else {
    echo json_encode(null);
}
?>