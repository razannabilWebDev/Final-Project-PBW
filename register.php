<?php 
session_start();
require 'config/koneksi.php';
require 'config/session.php';

cek_login_admin();

if (isset($_POST['register'])) {
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $username     = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $role   = mysqli_real_escape_string($conn, $_POST['role']);

    // Check if email already exists
    $email_check_query = "SELECT * FROM user WHERE email='$email'";
    $email_check_result = mysqli_query($conn, $email_check_query);

    if (mysqli_num_rows($email_check_result) > 0) {
        $error = "Gagal! Email sudah terdaftar.";
    } else {
    $password_hashed = password_hash($password, PASSWORD_DEFAULT);

    $cek_query = "SELECT * FROM user WHERE username='$username'";
    $cek_result = mysqli_query($conn, $cek_query);

    if (mysqli_num_rows($cek_result) > 0) {
        $error = "Gagal! username sudah terdaftar.";
    } else {
        
        $query = "INSERT INTO user (username, password, role) 
                  VALUES (?, ?, ?)";
        
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "sss", $username, $password_hashed, $role);

        if (mysqli_stmt_execute($stmt)) {
            $success = "Pengguna berhasil didaftarkan!";
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    }
    }
}   

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Warung Kelontong</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="assets/css/register.css">
</head>
<body>

<div class="floating-circle circle-1"></div>
<div class="floating-circle circle-2"></div>

<div class="register-wrapper">

    <!-- LEFT SIDE -->
    <div class="left-side">

        <div class="brand">
            <div class="brand-icon">
                <i class="fas fa-store"></i>
            </div>

            <div>
                <h1>Groceria</h1>
                <p>Sistem Informasi Warung Kelontong</p>
            </div>
        </div>

        <h2>Buat Akun & Mulai Kelola Warung Anda</h2>

        <p class="description">
            Daftarkan akun untuk mengakses dashboard modern dan mengelola stok,
            transaksi, supplier, pelanggan, serta laporan penjualan dengan lebih mudah.
        </p>

        <div class="benefits">
            <div class="benefit-item">
                <i class="fas fa-boxes-stacked"></i>
                <span>Monitoring Stok Real-time</span>
            </div>

            <div class="benefit-item">
                <i class="fas fa-users"></i>
                <span>Manajemen Pelanggan & Supplier</span>
            </div>

            <div class="benefit-item">
                <i class="fas fa-chart-pie"></i>
                <span>Laporan Penjualan Modern</span>
            </div>
        </div>

    </div>

    <!-- RIGHT SIDE -->
    <div class="right-side">

        <div class="register-card">

            <h2 class="register-title">Buat Akun</h2>
            <p class="register-subtitle">
                Lengkapi data berikut untuk membuat akun baru.
            </p>

            <form action="" method="POST">
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" placeholder="Masukkan email" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" class="form-control" name="username" placeholder="Masukkan username" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Role</label>
                    <select class="form-select" name="role" required>
                        <option value="" disabled selected>Pilih Role</option>
                        <option value="admin">Admin</option>
                        <option value="kasir">Kasir</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label">Password</label>

                    <div class="input-group">
                        <input type="password" class="form-control border-end-0" id="password" name="password" placeholder="Masukkan password" required>

                        <span class="input-group-text" onclick="togglePassword()">
                            <i class="fas fa-eye" id="eyeIcon"></i>
                        </span>
                    </div>
                </div>

                <button type="submit" name = "register" class="btn btn-register w-100">
                    <i class="fas fa-user-plus me-2"></i>
                    Daftar Sekarang
                </button>

            </form>

            <div class="login-link">
                Sudah punya akun?
                <a href="login.php">Login disini</a>
                <?php if(isset($error)): ?>
                <p style="color: red; font-size: 12px; text-align: center; margin-top: 5px;"><?= $error; ?></p>
            <?php endif; ?>
            <?php if(isset($success)): ?>
                <p style="color: green; font-size: 12px; text-align: center; margin-top: 5px;"><?= $success; ?></p>
            <?php endif; ?>
            </div>

        </div>

    </div>

</div>

<script>
    function togglePassword() {
        const password = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        if (password.type === 'password') {
            password.type = 'text';
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        } else {
            password.type = 'password';
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        }
    }
</script>

</body>
</html>