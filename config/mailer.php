<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

function kirimOTP($emailTujuan, $otp){

    $mail = new PHPMailer(true);

    try {

        $mail->isSMTP();

        $mail->Host = 'smtp.gmail.com';

        $mail->SMTPAuth = true;

        // EMAIL GMAIL
        $mail->Username = 'razannabilannadif@gmail.com';

        // APP PASSWORD GMAIL
        $mail->Password = 'zrbo qalb koxh hrkq';

        $mail->SMTPSecure = 'tls';

        $mail->Port = 587;

        $mail->setFrom(
            'razannabilannadif@gmail.com',
            'Warung Kelontong'
        );

        $mail->addAddress($emailTujuan);

        $mail->isHTML(true);

        $mail->Subject = 'Kode OTP Reset Password';

        $mail->Body = "
            <h2>Reset Password</h2>

            <p>Kode OTP anda:</p>

            <h1 style='letter-spacing:5px;'>$otp</h1>

            <p>Jangan bagikan kode ini kepada siapapun.</p>
        ";

        $mail->send();

        return true;

    } catch (Exception $e){

        return false;

    }

}
?>