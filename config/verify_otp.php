<?php

session_start();

$otp = $_POST['otp'];

if(time() > $_SESSION['otp_expired']){

    echo "
    <script>
        alert('OTP sudah expired');
        window.location='../forgot_password.php';
    </script>
    ";

    exit;
}

if($otp == $_SESSION['otp']){

    $_SESSION['otp_verified'] = true;

    header('Location: ../reset_password.php');

} else {

    echo "
    <script>
        alert('OTP salah');
        window.location='../verify_otp.php';
    </script>
    ";
}
?>