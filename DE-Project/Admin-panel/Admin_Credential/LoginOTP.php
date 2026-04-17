<?php
session_start();

// Already logged in
if (isset($_SESSION['AdminLoggedin']) && $_SESSION['AdminLoggedin'] == true) {
    header("Location: ./HomePage.php");
    exit();
}

// No OTP
if (!isset($_SESSION['otp'])) {
    header("Location: ./index.php");
    exit();
}

$error = "";

if (isset($_POST['verifyOTP'])) {

    $userOtp = $_POST['otp'];

    if ($userOtp == $_SESSION['otp']) {

        $_SESSION['AdminLoggedin'] = true;

        unset($_SESSION['otp']);

        header("Location: ./HomePage.php");
        exit();
    } else {
        $error = "Invalid OTP";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Admin OTP Verification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container d-flex justify-content-center align-items-center vh-100">

        <div class="card p-4 shadow" style="width:400px">

            <h4 class="text-center">Enter OTP</h4>

            <?php if ($error != "") echo "<div class='alert alert-danger'>$error</div>"; ?>

            <form method="post">
                <input type="text" name="otp" class="form-control mb-3 text-center" maxlength="6" required>
                <button name="verifyOTP" class="btn btn-dark w-100">Verify OTP</button>
            </form>

        </div>

    </div>

</body>

</html>