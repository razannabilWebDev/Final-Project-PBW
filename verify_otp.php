<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP</title>
    <link rel="stylesheet" href="assets/css/forgot_password.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container d-flex justify-content-center align-items-center vh-100">

    <div class="card border-0 shadow p-4 rounded-4" style="width:400px;">

        <h3 class="fw-bold text-center mb-4">
            Verifikasi OTP
        </h3>

        <form action="config/verify_otp.php" method="POST">

            <div class="mb-3">

                <label>Kode OTP</label>

                <input type="text"
                       name="otp"
                       maxlength="6"
                       class="form-control rounded-4 text-center"
                       required>

            </div>

            <button type="submit"
                    class="btn btn-success w-100 rounded-4">

                Verifikasi

            </button>

        </form>

    </div>

</div>

</body>
</html>