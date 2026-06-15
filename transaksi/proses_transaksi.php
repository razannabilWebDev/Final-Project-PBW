<?php
require_once '../config/koneksi.php';
header('Content-Type: application/json');

// Tangkap JSON dari JavaScript
$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!$data || empty($data['items'])) {
    echo json_encode(['status' => 'error', 'message' => 'Data transaksi tidak valid']);
    exit;
}

$id_user = $data['id_user'];
$id_pelanggan = $data['id_pelanggan'] ? $data['id_pelanggan'] : NULL;
$total_harga = $data['total_harga'];
$diskon = $data['diskon'];
$bayar = $data['bayar'];
$kembalian = $data['kembalian'];
$items = $data['items'];
$tukar_poin = $data['tukar_poin'];

// 1 Poin per kelipatan Rp 10.000 dari Total Akhir
$grand_total = $total_harga - $diskon;
$poin_didapat = floor($grand_total / 10000);

// Mulai Database Transaction agar aman
mysqli_begin_transaction($conn);

try {
    // 1. Insert tabel Transaksi Utama
    $query_trans = "INSERT INTO transaksi (id_user, id_pelanggan, total_harga, diskon, poin_didapat, bayar, kembalian) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt_trans = $conn->prepare($query_trans);
    $stmt_trans->bind_param("iiddidd", $id_user, $id_pelanggan, $total_harga, $diskon, $poin_didapat, $bayar, $kembalian );
    $stmt_trans->execute();
    
    // Ambil ID Transaksi baru
    $id_transaksi = $conn->insert_id; 

    // 2. Siapkan query untuk Detail & Stok
    $query_detail = "INSERT INTO detail_transaksi (id_transaksi, id_barang, jumlah, harga_jual, subtotal) VALUES (?, ?, ?, ?, ?)";
    $stmt_detail = $conn->prepare($query_detail);

    $query_stok = "UPDATE stok SET jumlah_stok = jumlah_stok - ? WHERE id_barang = ?";
    $stmt_stok = $conn->prepare($query_stok);

    // 3. Looping keranjang belanja
    foreach ($items as $item) {
        $subtotal_item = $item['qty'] * $item['harga'];
        
        // Simpan ke detail
        $stmt_detail->bind_param("iiidd", $id_transaksi, $item['id_barang'], $item['qty'], $item['harga'], $subtotal_item);
        $stmt_detail->execute();

        // Kurangi stok
        $stmt_stok->bind_param("ii", $item['qty'], $item['id_barang']);
        $stmt_stok->execute();
    }

    // 4. Update Poin Pelanggan (jika yang beli adalah member)
    if ($id_pelanggan) {
        $poin_potong = $tukar_poin ? 100 : 0;
        $query_pelanggan = "UPDATE pelanggan SET poin_member = poin_member + ? - ? WHERE id_pelanggan = ?";
        $stmt_pelanggan = $conn->prepare($query_pelanggan);
        $stmt_pelanggan->bind_param("iii", $poin_didapat, $poin_potong, $id_pelanggan);
        $stmt_pelanggan->execute();
    }

    // COMMIT (Simpan permanen jika semua query di atas berhasil)
    mysqli_commit($conn);

    echo json_encode([
        'status' => 'success', 
        'id_transaksi' => $id_transaksi
    ]);

} catch (Exception $e) {
    // ROLLBACK (Batalkan semua jika ada 1 saja yang gagal/error)
    mysqli_rollback($conn);
    echo json_encode([
        'status' => 'error', 
        'message' => $e->getMessage()
    ]);
}
?>