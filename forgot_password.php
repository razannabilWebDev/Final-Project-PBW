<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/forgot_password.css">
</head>

<body>

<div class="container d-flex justify-content-center align-items-center vh-100">

    <div class="card border-0 shadow p-4 rounded-4" style="width:400px;">

        <h3 class="fw-bold text-center mb-4">
            Lupa Password
        </h3>

        <form action="config/send_otp.php" method="POST">

            <div class="mb-3">

                <label>Email</label>

                <input type="email"
                       name="email"
                       class="form-control rounded-4"
                       placeholder="Masukkan email terdaftar"
                       required>

            </div>

            <button type="submit"
                    class="btn btn-success w-100 rounded-4">

                Kirim OTP

            </button>

        </form>

    </div>

</div>

</body>
</html>