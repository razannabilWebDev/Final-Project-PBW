<?php
session_start();

if(!isset($_SESSION['otp_verified'])){
    header('Location: forgot_password.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body style="background:#f4f6f3;">

<div class="container d-flex justify-content-center align-items-center vh-100">

    <div class="card border-0 shadow p-4 rounded-4" style="width:400px;">

        <h3 class="fw-bold text-center mb-4">
            Reset Password
        </h3>

        <form action="config/reset_password_process.php" method="POST">

            <div class="mb-3">

                <label>Password Baru</label>

                <input type="password"
                       name="password"
                       class="form-control rounded-4"
                       required>

            </div>

            <button type="submit"
                    class="btn btn-success w-100 rounded-4">

                Simpan Password

            </button>

        </form>

    </div>

</div>

</body>
</html>