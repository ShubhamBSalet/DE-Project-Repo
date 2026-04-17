<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require $_SERVER['DOCUMENT_ROOT'] . '/DE-Project/PHPMailer/src/Exception.php';
require $_SERVER['DOCUMENT_ROOT'] . '/DE-Project/PHPMailer/src/PHPMailer.php';
require $_SERVER['DOCUMENT_ROOT'] . '/DE-Project/PHPMailer/src/SMTP.php';

include("../../_DBConnect.php");

if (isset($_POST['login'])) {

    $email = trim($_POST['email']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format";
        header("Location: Login.php");
        exit();
    }

    // Check email
    $stmt = $conn->prepare("SELECT * FROM studentdata WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $_SESSION['error'] = "Email not found";
        header("Location: ./Login.php");
        exit();
    }

    // Generate OTP
    $otp = rand(100000, 999999);

    $_SESSION['otp'] = $otp;
    $_SESSION['email'] = $email;
    $_SESSION['otp_time'] = time();

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;

        $mail->Username = 'yourgmail@gmail.com';    //change mail
        $mail->Password = 'your_app_password';      //change password

        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('yourgmail@gmail.com', 'DE Project');    //change mail

        $mail->isHTML(true);
        $mail->Subject = 'Login OTP';
        $mail->Body = "<h2>Your OTP: $otp</h2>";

        $mail->send();

        header("Location: LoginOTP.php");
        exit();
    } catch (Exception $e) {
        $_SESSION['error'] = "Mailer Error: " . $mail->ErrorInfo;
        header("Location: Login.php");
        exit();
    }
}
