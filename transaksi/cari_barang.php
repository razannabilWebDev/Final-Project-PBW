<?php
require_once '../config/koneksi.php';
header('Content-Type: application/json');

$q = isset($_GET['q']) ? $_GET['q'] : '';

// Mengambil barang beserta stoknya (Hanya tampilkan yang stoknya > 0 jika ingin dibatasi)
$query = "SELECT b.id_barang, b.nama_barang, b.harga_jual, s.jumlah_stok 
          FROM barang b 
          LEFT JOIN stok s ON b.id_barang = s.id_barang 
          WHERE b.nama_barang LIKE ? OR b.id_barang LIKE ? LIMIT 5";

$stmt = $conn->prepare($query);
$search = "%$q%";
$stmt->bind_param("ss", $search, $search);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while($row = $result->fetch_assoc()) {
    // Pastikan nilai NULL dari left join jadi 0
    $row['jumlah_stok'] = $row['jumlah_stok'] ? $row['jumlah_stok'] : 0; 
    $data[] = $row;
}
echo json_encode($data);
?>