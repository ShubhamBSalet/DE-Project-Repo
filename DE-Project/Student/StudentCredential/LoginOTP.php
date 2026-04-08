<?php
session_start();
include("../../_DBConnect.php");

//****************if student logged in****************
if (isset($_SESSION['studentLoggedin']) && $_SESSION['studentLoggedin'] == true) {
    header("Location: HomePage.php");
    exit();
}

//****************if faculty logged in****************
if (isset($_SESSION['facultyLoggedin']) && $_SESSION['facultyLoggedin'] == true) {
    header("Location: ./Faculty/FacultyCredential/HomePage.php");
    exit();
}

$error = "";

// check otp session
if (!isset($_SESSION['otp'])) {
    header("Location: Login.php");
    exit();
}

// verify OTP
if (isset($_POST['verifyOTP'])) {

    $userOtp = $_POST['otp'];

    if ($userOtp == $_SESSION['otp']) {

        $_SESSION['studentLoggedin'] = true;

        $email = $_SESSION['email'];

        $query = "SELECT * FROM studentdata WHERE email='$email'";
        $q = mysqli_query($conn, $query);

        $row = mysqli_fetch_assoc($q);

        $_SESSION['enrollment'] = $row['enrollment'];

        unset($_SESSION['otp']);

        header("Location: HomePage.php");
        exit();

    } else {
        $_SESSION['studentLoggedin'] = false;
        $error = "Invalid OTP";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>

    <!-- Latest Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container d-flex justify-content-center align-items-center min-vh-100">

        <!-- LARGE CARD -->
        <div class="card shadow-lg border-0 rounded-4 col-12 col-sm-10 col-md-6 col-lg-5 col-xl-4">

            <div class="card-body p-5 text-center">

                <h2 class="fw-bold mb-3">OTP Verification</h2>
                <p class="text-muted mb-4">Enter the 6-digit code sent to your email</p>

                <!-- Demo OTP (remove in production) -->
                <div class="alert alert-info py-2">
                    OTP: <?php echo $_SESSION['otp']; ?>
                </div>

                <?php
                if ($error != "") {
                    echo "<div class='alert alert-danger'>$error</div>";
                }
                ?>

                <form method="post" action="./LoginOTP.php">

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
                        class="btn btn-primary btn-lg w-100 rounded-pill">
                        Verify OTP
                    </button>

                </form>


            </div>

        </div>

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>