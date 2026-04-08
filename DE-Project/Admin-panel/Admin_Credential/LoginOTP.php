<?php
session_start();

// If already logged in
if (isset($_SESSION['AdminLoggedin']) && $_SESSION['AdminLoggedin'] == true) {
    header("Location: ./HomePage.php");
    exit();
}

// If OTP not generated
if (!isset($_SESSION['otp'])) {
    header("Location: ./index.php");
    exit();
}

$error = "";

if (isset($_POST['verifyOTP'])) {

    $userOtp = $_POST['otp'];

    if ($userOtp == $_SESSION['otp']) {

        $_SESSION['AdminLoggedin'] = true;

        // No need to reassign email again
        // $_SESSION['email'] already exists

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
    <title>Admin - OTP Verification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container d-flex justify-content-center align-items-center vh-100">

        <div class="card p-4 shadow" style="width:400px">

            <h4 class="text-center">Enter OTP</h4>

            <p class="text-center text-muted">
                OTP: <?php echo $_SESSION['otp']; ?>
            </p>

            <?php
            if ($error != "") {
                echo "<div class='alert alert-danger'>$error</div>";
            }
            ?>

            <form method="post" action="./LoginOTP.php">
                <input type="text" name="otp" class="form-control mb-3" placeholder="Enter 6 digit OTP" required maxlength="6" pattern="[0-9]{6}" inputmode="numeric" oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,6)">
                <button name="verifyOTP" class="btn btn-dark w-100">Verify OTP</button>
            </form>

        </div>

    </div>

</body>

</html>