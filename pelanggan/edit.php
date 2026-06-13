<?php
session_start();

if (!isset($_SESSION['role'])) {
    header("Location: ../login.php");
    exit;
}

$role_sekarang = $_SESSION['role']; 

$koneksi = mysqli_connect("localhost", "root", "", "warung_kelontong");

if (!isset($_GET['id_pelanggan']) || empty($_GET['id_pelanggan'])) {
    header("Location: index.php");
    exit;
}

$id_pelanggan = mysqli_real_escape_string($koneksi, $_GET['id_pelanggan']);

$query = "SELECT * FROM pelanggan WHERE id_pelanggan = '$id_pelanggan'";
$result = mysqli_query($koneksi, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pelanggan</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>

<body>
    <div class="wrapper d-flex min-vh-100">
        
        <div>
            <?php include '../templates/sidebar.php'; ?>
        </div>
        
        <div class="main-content">
            
            <div class="content-body">
                
                <div class="mb-4">
                    <h2 class="fw-bold mb-1">Kelola Pelanggan</h2>
                    <p class="text-muted mb-3">Kawasan kelola pelanggan</p>
                </div>

                <div class="card border-0 shadow rounded-4 mx-auto" style="max-width: 800px;">
                    <div class="card-body p-5">
                        
                        <div class="text-center mb-5">
                            <span class="btn text-white fw-bold px-5 py-2 fs-5 rounded-3" style="background-color: #435c49; pointer-events: none;">
                                Edit Pelanggan
                            </span>
                        </div>

                        <form action="proses_edit.php" method="POST">
                            
                            <input type="hidden" name="id_pelanggan" value="<?php echo $data['id_pelanggan']; ?>">

                            <div class="mb-4">
                                <label for="nama_pelanggan" class="form-label fw-bold text-dark fs-5 mb-2">Nama Pelanggan</label>
                                <input type="text" class="form-control form-control-lg bg-light border-0 rounded-3 text-secondary py-3" id="nama_pelanggan" name="nama_pelanggan" value="<?php echo htmlspecialchars($data['nama_pelanggan']); ?>" required>
                            </div>

                            <div class="mb-4">
                                <label for="alamat" class="form-label fw-bold text-dark fs-5 mb-2">Alamat Pelanggan</label>
                                <input type="text" class="form-control form-control-lg bg-light border-0 rounded-3 text-secondary py-3" id="alamat" name="alamat" value="<?php echo htmlspecialchars($data['alamat']); ?>" required>
                            </div>

                            <div class="mb-4">
                                <label for="no_hp" class="form-label fw-bold text-dark fs-5 mb-2">No. Handphone</label>
                                <input type="text" class="form-control form-control-lg bg-light border-0 rounded-3 text-secondary py-3" id="no_hp" name="no_hp" value="<?php echo htmlspecialchars($data['no_hp']); ?>" required>
                            </div>

                            <div class="mb-5">
                                <label for="poin_member" class="form-label fw-bold text-dark fs-5 mb-2">Poin</label>
                                <input type="number" class="form-control form-control-lg bg-light border-0 rounded-3 text-secondary py-3" id="poin_member" name="poin_member" value="<?php echo htmlspecialchars($data['poin_member']); ?>" required>
                            </div>

                            <div class="d-flex justify-content-center gap-3 mt-4">
                                <button type="submit" name="simpan" class="btn text-white fw-semibold px-5 py-2 rounded-3" style="background-color: #435c49;">
                                    Simpan
                                </button>
                                <a href="index.php" class="btn text-white fw-semibold px-5 py-2 rounded-3" style="background-color: #435c49">
                                    Batal
                                </a>
                            </div>

                        </form>

                    </div>
                </div> 
            </div>
                <footer>
                    <?php include '../templates/footer.php'; ?>
                </footer>
        </div>
    </div>
</body>
</html>