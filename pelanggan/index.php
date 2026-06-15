<?php
session_start();

if (!isset($_SESSION['role'])) {
    header("Location: ../login.php"); 
    exit;
}

$role_sekarang = $_SESSION['role']; 

$koneksi = mysqli_connect("localhost", "root", "", "warung_kelontong");

$search_query = "";

if (isset($_GET['cari']) && !empty($_GET['cari'])) {
    $cari = mysqli_real_escape_string($koneksi, $_GET['cari']);

    $search_query = " WHERE nama_pelanggan LIKE '%$cari%' OR no_hp LIKE '%$cari%'";
}

$query = "SELECT * FROM pelanggan" . $search_query;
$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pelanggan</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>

<body>
    <div class="wrapper d-flex max-vh-100">
        
        <div>
            <?php include '../templates/sidebar.php'; ?>
        </div>
        
        <div class="main-content">
            <div class="content-body">
                <div class="mb-4">
                    <h2 class="fw-bold mb-1">Kelola Pelanggan</h2>
                    <p class="text-muted mb-3">Kawasan kelola pelanggan</p>
                    
                        <div class="card-body p-4.5">
                            <div class="d-flex align-items-center justify-content-between gap-4 w-100">
                                <form action="" method="GET" class="flex-grow-1" style="max-width: 65%;">
                                    <div class="position-relative">
                                        <input type="text" name="cari" class="form-control" placeholder="Cari Nama / No. HP..." 
                                                value="<?php echo isset($_GET['cari']) ? htmlspecialchars($_GET['cari']) : ''; ?>" 
                                                style="border: 1px solid #7a7a7a; height: 48px;">
        
                                        <button type="submit" class="btn p-0 position-absolute end-0 top-50 translate-middle-y me-4 text-secondary border-0 bg-transparent">
                                            <i class="fa-solid fa-magnifying-glass fs-5"></i>
                                        </button>
                                    </div>
                                </form>
                                <a href="tambah.php" class="btn btn-primary rounded-4">
                                    <i class="fas fa-plus me-2"></i>Tambah Pelanggan
                                </a>

                            </div>
                        </div>
                        
                        <div class="card border-0 rounded-4">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table align-middle" >
                                        <thead>
                                            <tr>
                                            <th>ID</th>
                                            <th>Nama Pelanggan</th>
                                            <th>Alamat</th>
                                            <th>No.HP</th>
                                            <th>Poin Member</th>
                                            <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            while ($row = mysqli_fetch_assoc($result)) { 
                                            ?>
                                            <tr>
                                            <td><?php echo $row['id_pelanggan']; ?></td>
                                            <td><?php echo $row['nama_pelanggan']; ?></td>
                                            <td><?php echo $row['alamat']; ?></td>
                                            <td><?php echo $row['no_hp']; ?></td>
                                            <td><?php echo $row['poin_member']; ?></td>
                                            <td>
                                                <div class="d-flex align-items-center gap-3">
                                                    <a href="edit.php?id_pelanggan=<?php echo $row['id_pelanggan']; ?>" 
                                                        class="text-dark fs-5 link-underline link-underline-opacity-0" 
                                                        title="Edit Data">
                                                    <i class="fa-regular fa-pen-to-square"></i>
                                                    </a>
                            
                                                        <?php if ($role_sekarang === 'admin') { ?>
                                                        <a href="hapus.php?id_pelanggan=<?php echo $row['id_pelanggan']; ?>" 
                                                            onclick="return confirm('Ingin menghapus data ini?')" 
                                                            class="text-dark fs-5 link-underline link-underline-opacity-0" 
                                                            title="Hapus Data">
                                                        <i class="fa-solid fa-trash-can"></i>
                                                        </a>
                                                <?php }?>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div> 
            </div>
        </div> 
        <?php include '../templates/footer.php'; ?>
    </div> 
    
</body>
</html>