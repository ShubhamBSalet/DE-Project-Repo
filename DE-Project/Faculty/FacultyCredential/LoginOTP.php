<?php
    session_start();

    //****************if faculty not logged in****************
    if (isset($_SESSION['facultyLoggedin']) && $_SESSION['facultyLoggedin'] == true) {
        header("Location: HomePage.php");
        exit();
    }
    
    //****************if student logged in****************
    if (isset($_SESSION['studentLoggedin']) && $_SESSION['studentLoggedin'] == true) {
        header("Location: ./Student/StudentCredential/HomePage.php");
        exit();
    }

$error = "";

// check form contains value
if (!isset($_SESSION['otp'])) {
    header("Location: Login.php");
    exit();
}

// check verifyOTP session contains value or not
if (isset($_POST['verifyOTP'])) {

    $userOtp = $_POST['otp'];

    // comparing user enterd OTP with server session OTP matched
    if ($userOtp == $_SESSION['otp']) {

        // making new session for user login
        $_SESSION['facultyLoggedin'] = true;

        // create new session variable for email which stores the email from login page 
        $_SESSION['email'] = $_SESSION['email'];

        // OTP session variable will be destroy
        unset($_SESSION['otp']);

        header("Location: HomePage.php");
        exit();

    } 
    else {
        // make user logged in session false for not logged in
        $_SESSION['facultyLoggedin'] = false;
        $error = "Invalid OTP";
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Faculty - OTP Verification</title>
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
                <button name="verifyOTP" class="btn btn-success w-100">Verify OTP</button>
            </form>

        </div>

    </div>

</body>

</html>