<?php
    session_start();
    include("../../_DBConnect.php");

    //****************if student logged in****************
    if (isset($_SESSION['studentLoggedin']) && $_SESSION['studentLoggedin'] == true) {
        header("Location: /DE-Project/Student/StudentCredential/HomePage.php");
        exit();
    }

    //****************if faculty logged in****************
    if (isset($_SESSION['facultyLoggedin']) && $_SESSION['facultyLoggedin'] == true) {
        header("Location: /DE-Project/Faculty/FacultyCredential/HomePage.php");
        exit();
    }

    $error = "";

    //*****************check otp session not contains value*****************
    if (!isset($_SESSION['otp'])) {
        header("Location: Login.php");
        exit();
    }

    //*****************check form contains data*****************
    if (isset($_POST['verifyOTP'])) {

        // get input otp value from the form
        $userOtp = $_POST['otp'];

        // check user input & accessing session variable for access value of "otp" from login.php are same
        if ($userOtp == $_SESSION['otp']) {

            // make session variable for user logged in successfull 
            $_SESSION['studentLoggedin'] = true;

            // access email from session variable from login.php
            $email = $_SESSION['email'];

            // query to acces student enrollment from email
            $query = "SELECT * FROM studentdata WHERE email='$email'";
            $q = mysqli_query($conn, $query);

            // store the row from table in an array formate
            $row = mysqli_fetch_assoc($q);

            // make session variable for enrollment 
            $_SESSION['enrollment'] = $row['enrollment'];

            // destroy the value of session varible otp
            unset($_SESSION['otp']);

            // redirect to student homepage
            header("Location: /DE-Project/Student/StudentCredential/HomePage.php");
            exit();
        } 
        else {
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

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-body-tertiary">

<div class="container d-flex justify-content-center align-items-center min-vh-100">

    <div class="col-12 col-sm-10 col-md-6 col-lg-5 col-xl-4">

        <div class="card shadow border-0 rounded-4">

            <div class="card-body p-5 text-center">

                <!-- Icon -->
                <div class="mb-3">
                    <i class="bi bi-shield-lock-fill text-primary" style="font-size: 2.5rem;"></i>
                </div>

                <!-- Title -->
                <h3 class="fw-bold mb-2">OTP Verification</h3>

                <!-- Subtitle -->
                <p class="text-muted mb-4">
                    Enter the 6-digit code sent to your email
                </p>

                <!-- Error -->
                <?php
                if ($error != "") {
                    echo "<div class='alert alert-danger py-2'>$error</div>";
                }
                ?>

                <form method="post" action="./LoginOTP.php">

                    <!-- OTP Input -->
                    <div class="form-floating mb-4">
                        <input type="text"
                               name="otp"
                               class="form-control text-center"
                               id="otp"
                               placeholder="Enter OTP"
                               maxlength="6"
                               pattern="[0-9]{6}"
                               inputmode="numeric"
                               required
                               oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,6)">
                        <label for="otp">
                            <i class="bi bi-key me-1"></i> Enter OTP
                        </label>
                    </div>

                    <!-- Button -->
                    <button name="verifyOTP"
                            class="btn btn-primary w-100 py-2 rounded-pill">
                        <i class="bi bi-check-circle me-1"></i> Verify OTP
                    </button>

                </form>

                <!-- Extra small help -->
                <p class="text-muted mt-4 mb-0 small">
                    Didn't receive code? Check spam or try again.
                </p>

            </div>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>