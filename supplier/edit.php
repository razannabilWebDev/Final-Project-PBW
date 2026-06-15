<?php
session_start();
require_once '../config/koneksi.php';
require_once '../config/session.php';
cek_login();

if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    header("Location: ../login.php");
    exit;
}

if ($_SESSION['role'] !== 'admin') {
    echo "<script>
        alert('Akses Ditolak! Halaman ini hanya dapat diakses oleh Admin.');
        window.location.href = '../dashboard/kasir.php';
    </script>";
    exit;
}


if (!isset($_GET['id_supplier'])) {
    header("Location: index.php");
    exit;
}
$id_supplier = mysqli_real_escape_string($conn, $_GET['id_supplier']);

$query_select = "SELECT * FROM supplier WHERE id_supplier = '$id_supplier'";
$result_select = mysqli_query($conn, $query_select);

if (mysqli_num_rows($result_select) == 0) {
    echo "<script>
        alert('Data supplier tidak ditemukan di database!');
        window.location.href = 'index.php';
    </script>";
    exit;
}

$data = mysqli_fetch_assoc($result_select);

if (isset($_POST['update'])) {
    $nama   = mysqli_real_escape_string($conn, $_POST['nama_supplier']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $telp   = mysqli_real_escape_string($conn, $_POST['no_telepon']);
    $email  = mysqli_real_escape_string($conn, $_POST['email']);
    
    $query_update = "UPDATE supplier SET 
                        nama_supplier = '$nama', 
                        alamat = '$alamat', 
                        no_telepon = '$telp', 
                        email = '$email' 
                     WHERE id_supplier = '$id_supplier'";
                     
    $eksekusi = mysqli_query($conn, $query_update);
    
    if ($eksekusi) {
        echo "<script>
            alert('Data supplier berhasil diperbarui!');
            window.location.href = 'index.php';
        </script>";
        exit;
    } else {
        echo "<script>
            alert('Gagal memperbarui data: " . mysqli_error($conn) . "');
        </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Supplier - Groceria</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
    
    <style>
        body {
            background-color: #ffffff;
        }

        .content-body {
            background-color: #E6EADF;
        }

        .form-card-custom {
            background-color: #1A2B20; 
            border-radius: 20px;
            padding: 40px;
            color: #ffffff;
            box-shadow: 0 8px 24px rgba(0,0,0,0.15);
        }

        .form-card-custom .form-label {
            font-weight: 700;
            font-size: 14px;
            color: #ffffff;
            margin-bottom: 8px;
        }

        .form-card-custom .form-control {
            background: linear-gradient(145deg, #253A2C, #2E4737);
            border: 1px solid #365340;
            border-radius: 30px; 
            height: 48px;
            color: #ffffff;
            padding-left: 20px;
            padding-right: 20px;
            font-size: 14px;
            box-shadow: inset 2px 2px 5px rgba(0,0,0,0.3), inset -2px -2px 5px rgba(255,255,255,0.05);
        }

        /* Fokus Input */
        .form-card-custom .form-control:focus {
            background: linear-gradient(145deg, #2A4232, #35523F);
            border-color: #4B7359;
            color: #ffffff;
            box-shadow: 0 0 0 0.25rem rgba(75, 115, 89, 0.25);
            outline: 0;
        }

        .btn-cancel-custom {
            background-color: #A3A89E;
            color: #000000;
            border: none;
            border-radius: 15px;
            padding: 10px 35px;
            font-weight: 700;
            font-size: 15px;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .btn-cancel-custom:hover {
            background-color: #8E938A;
            color: #000000;
        }

        .btn-submit-custom {
            background-color: #B2B7AC;
            color: #000000;
            border: none;
            border-radius: 15px;
            padding: 10px 35px;
            font-weight: 700;
            font-size: 15px;
            transition: all 0.2s;
        }
        .btn-submit-custom:hover {
            background-color: #9FA499;
        }

        .main-content footer {
            border-top: none;
            border: none;
            box-shadow: none;
        }
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
                
                <div class="container-fluid p-0">
                    <div class="row">
                        <div class="col-xl-10 col-lg-12">
                            
                            <div class="form-card-custom">
                                <h3 class="fw-bold mb-4" style="font-size: 22px;">Edit Supplier</h3>
                                
                                <form action="" method="POST">
                                    <div class="mb-4">
                                        <label for="nama_supplier" class="form-label">Nama Supplier</label>
                                        <input type="text" class="form-control" id="nama_supplier" name="nama_supplier" required value="<?php echo htmlspecialchars($data['nama_supplier']); ?>">
                                    </div>

                                    <div class="mb-4">
                                        <label for="alamat" class="form-label">Alamat</label>
                                        <input type="text" class="form-control" id="alamat" name="alamat" required value="<?php echo htmlspecialchars($data['alamat']); ?>">
                                    </div>

                                    <div class="row mb-5">
                                        <div class="col-md-6 mb-4 mb-md-0">
                                            <label for="no_telepon" class="form-label">No. Telepon</label>
                                            <input type="text" class="form-control" id="no_telepon" name="no_telepon" required value="<?php echo htmlspecialchars($data['no_telepon']); ?>">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="email" name="email" required value="<?php echo htmlspecialchars($data['email']); ?>">
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-center gap-4 mt-3">
                                        <a href="index.php" class="btn btn-cancel-custom">Cancel</a>
                                        <button type="submit" name="update" class="btn btn-submit-custom">Update Supplier</button>
                                    </div>
                                </form>
                                
                            </div>

                        </div>
                    </div>
                </div>

            </div> 
            
                    <?php include '../templates/footer.php'; ?>
            
        </div> 
    </div> 
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>