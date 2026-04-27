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

<body class="bg-body-tertiary">

<div class="container d-flex justify-content-center align-items-center min-vh-100">

    <div class="col-md-5 col-lg-4">

        <div class="card shadow border-0 rounded-4 p-4">

            <!-- HEADER -->
            <div class="text-center mb-4">
                <h3 class="fw-bold">🔐 OTP Verification</h3>
                <p class="text-muted mb-0">
                    Enter the 6-digit code sent to your email
                </p>
            </div>

            <!-- ERROR -->
            <?php if ($error != "") { ?>
                <div class="alert alert-danger text-center rounded-3 py-2">
                    <?php echo $error; ?>
                </div>
            <?php } ?>

            <!-- FORM -->
            <form method="post">

                <div class="mb-4">

                    <input type="text"
                           name="otp"
                           class="form-control form-control-lg text-center rounded-pill"
                           placeholder="Enter OTP"
                           maxlength="6"
                           pattern="[0-9]{6}"
                           inputmode="numeric"
                           required
                           oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,6)">

                </div>

                <button name="verifyOTP"
                        class="btn btn-dark w-100 rounded-pill py-2">
                    Verify OTP
                </button>

            </form>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>