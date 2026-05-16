<?php 
session_start();
require 'config/koneksi.php';
require 'config/session.php';

if (isset($_POST['masuk'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $query = "SELECT * FROM user WHERE username= ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);    

    if (mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['password'])) {
        // if ($password === $user['password']) {
            $_SESSION['login'] = true;
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            if ($user['role'] === 'admin') {
                header("Location: dashboard/admin.php");
                exit;
            } elseif ($user['role'] === 'kasir') {
                header("Location: dashboard/kasir.php");
                exit;
            }
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Username tidak ditemukan!";
    }
}


?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Warung Kelontong</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="assets/css/login.css">
</head>
<body>

    <div class="floating-circle circle-1"></div>
    <div class="floating-circle circle-2"></div>

    <div class="login-wrapper">

        <!-- LEFT SIDE -->
        <div class="left-side">

            <div class="brand">
                <div class="brand-icon">
                    <i class="fas fa-store"></i>
                </div>

                <div>
                    <h1>WK Store</h1>
                    <p>Sistem Informasi Warung Kelontong</p>
                </div>
            </div>

            <h2>Kelola Warung Jadi Lebih Modern & Efisien</h2>

            <p class="description">
                Sistem manajemen warung kelontong modern untuk mengelola barang,
                transaksi, supplier, pelanggan, dan laporan penjualan dalam satu dashboard.
            </p>

            <div class="feature-list">
                <div class="feature-item">
                    <i class="fas fa-box"></i>
                    <span>Manajemen Barang & Stok</span>
                </div>

                <div class="feature-item">
                    <i class="fas fa-cash-register"></i>
                    <span>Transaksi Cepat & Praktis</span>
                </div>

                <div class="feature-item">
                    <i class="fas fa-chart-line"></i>
                    <span>Laporan Penjualan Interaktif</span>
                </div>
            </div>

        </div>

        <!-- RIGHT SIDE -->
        <div class="right-side">

            <div class="login-card">

                <h2 class="login-title">Selamat Datang</h2>
                <p class="login-subtitle">
                    Silakan login untuk masuk ke dashboard.
                </p>

                <form action="" method="POST">

                    <div class="mb-4">
                        <label class="form-label">Username</label>
                        <input type="text" class="form-control" name="username" placeholder="Masukkan username" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>

                        <div class="input-group">
                            <input type="password" class="form-control border-end-0" id="password" name="password" placeholder="Masukkan password" required>

                            <span class="input-group-text" onclick="togglePassword()">
                                <i class="fas fa-eye" id="eyeIcon"></i>
                            </span>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="remember">
                            <label class="form-check-label" for="remember">
                                Ingat saya
                            </label>
                        </div>

                        <a href="#" class="forgot-password">
                            Lupa password?
                        </a>
                    </div>

                    <button type="submit" name="masuk" class="btn btn-login w-100">
                        <i class="fas fa-right-to-bracket me-2"></i>
                        Masuk Dashboard
                    </button>

                </form>

                <div class="footer-text">
                    © 2026 Warung Kelontong Management System
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
