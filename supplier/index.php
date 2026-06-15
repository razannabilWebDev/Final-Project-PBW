<?php
session_start();

if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    header("Location: ../login.php");
    exit;
}

if ($_SESSION['role'] !== 'admin') {
    echo "<script>
        alert('Akses Ditolak! Modul Supplier hanya dapat diakses oleh Admin.');
        window.location.href = '../dashboard/kasir.php';
    </script>";
    exit;
}

include '../config/koneksi.php'; 

if (isset($_GET['cari']) && !empty($_GET['cari'])) {
    $keyword = mysqli_real_escape_string($conn, $_GET['cari']);
    $query = "SELECT * FROM supplier WHERE nama_supplier LIKE '%$keyword%' OR alamat LIKE '%$keyword%' ORDER BY id_supplier DESC";
} else {
    $query = "SELECT * FROM supplier ORDER BY id_supplier DESC";
}

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Supplier - Groceria</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
    
    <style>
        body { background-color: #ffffff; }
        .content-body { background-color: #E6EADF; }
        .search-container { position: relative; width: 250px; }
        .search-container .form-control {
            height: 42px; padding-left: 45px; padding-right: 20px;
            border-radius: 30px; border: 1px solid #ced4da; background-color: #ffffff; font-size: 14px;
        }
        .search-container .search-icon {
            position: absolute; left: 20px; top: 50%; transform: translateY(-50%); color: #6c757d;
        }
        .btn-tambah-custom {
            background-color: #2E4332; color: #ffffff; border-radius: 30px;
            padding: 0 25px; height: 42px; font-weight: 600; font-size: 14px;
            display: inline-flex; align-items: center; justify-content: center;
        }
        .btn-tambah-custom:hover { opacity: 0.9; color: #ffffff; }
        .main-content footer { border-top: none; border: none; box-shadow: none; }
    </style>
</head>
<body>
    <div class="wrapper d-flex max-vh-100">
        
        <div class="flex-shrink-0">
            <?php include '../templates/sidebar.php'; ?>
        </div>
        
        <div class="main-content flex-grow-1 d-flex flex-column">
            
            <div class="content-body flex-grow-1 p-5">
                
                <div class="mb-5">
                    <h2 class="fw-bold mb-1" style="font-size: 28px; color: #000000;">Kelola Supplier</h2>
                    <p class="text-muted mb-0" style="font-size: 14px;">Selamat datang di sistem informasi warung kelontong</p>
                </div>
                
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div class="d-inline-flex align-items-center gap-3 text-white px-4 py-2 rounded-4 shadow-sm" style="background-color: #2E4332; height: 55px;">
                        <i class="fas fa-truck fa-lg"></i>
                        <span class="fw-bold fs-5">Manajemen Supplier</span>
                    </div>
                    
                    <div class="d-flex align-items-center gap-3">
                        <form action="" method="GET" class="mb-0">
                            <div class="search-container">
                                <i class="fa-solid fa-magnifying-glass search-icon"></i>
                                <input type="text" name="cari" class="form-control" placeholder="Cari supplier..." value="<?php echo isset($_GET['cari']) ? htmlspecialchars($_GET['cari']) : ''; ?>">
                            </div>
                        </form>
                        <a href="tambah.php" class="btn btn-tambah-custom">
                            <i class="fas fa-plus me-2"></i> Tambah Supplier
                        </a>
                    </div>
                </div>

                <div class="card border-0 rounded-4 shadow-sm overflow-hidden">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0 text-center">
                                <thead class="table-light" style="background-color: #f8f9fa;">
                                    <tr>
                                        <th style="padding: 18px; font-weight: 700;">ID</th>
                                        <th class="text-start" style="font-weight: 700;">Nama Supplier</th>
                                        <th class="text-start" style="font-weight: 700;">Alamat</th>
                                        <th style="font-weight: 700;">No. Telepon</th>
                                        <th style="font-weight: 700;">Email</th>
                                        <th style="font-weight: 700;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php 
                                    if (mysqli_num_rows($result) > 0) {
                                        while ($row = mysqli_fetch_assoc($result)) : 
                                    ?>
                                    <tr>
                                        <td style="padding: 18px;"><?php echo $row['id_supplier']; ?></td>
                                        <td class="text-start fw-bold"><?php echo htmlspecialchars($row['nama_supplier']); ?></td>
                                        <td class="text-start text-muted"><?php echo htmlspecialchars($row['alamat']); ?></td>
                                        <td><?php echo htmlspecialchars($row['no_telepon']); ?></td>
                                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-center gap-3">
                                                <a href="edit.php?id_supplier=<?php echo $row['id_supplier']; ?>" class="text-dark fs-5" title="Edit">
                                                    <i class="fa-regular fa-pen-to-square"></i>
                                                </a>
                                                <a href="hapus.php?id_supplier=<?php echo $row['id_supplier']; ?>" onclick="return confirm('Yakin hapus data ini?')" class="text-danger fs-5" title="Hapus">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php 
                                        endwhile; 
                                    } else { 
                                    ?>
                                    <tr>
                                        <td colspan="6" class="text-center p-4 text-muted">Data supplier tidak ditemukan atau masih kosong.</td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div> 
            
                <div class="container-fluid px-5">
                    
                </div>
            
        </div> 
    </div> 
    <?php include '../templates/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>