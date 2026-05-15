<!DOCTYPE html>
<html lang="id">
<head>

    <meta charset="UTF-8">

    <title>Login</title>

    <link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.min.css">

    <style>

        body{
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg,#1e3a8a,#2563eb);
        }

        .login-box{
            width: 400px;
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }

    </style>

</head>

<body>

<div class="login-box">

    <h2 class="text-center mb-4">
        Login Sistem
    </h2>

    <form action="auth/proses_login.php" method="POST">

        <div class="mb-3">
            <label>Username</label>

            <input type="text"
                   name="username"
                   class="form-control">
        </div>

        <div class="mb-3">
            <label>Password</label>

            <input type="password"
                   name="password"
                   class="form-control">
        </div>

        <button class="btn btn-primary w-100">
            Login
        </button>

    </form>

</div>

</body>
</html>