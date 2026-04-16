<?php
session_start();

//****************if faculty logged in****************
if (isset($_SESSION['facultyLoggedin']) && $_SESSION['facultyLoggedin'] == true) {
    // header("Location: HomePage.php");
    header("Location: /DE-Project/Faculty/FacultyCredential/HomePage.php");
    exit();
}

//****************if student logged in****************
if (isset($_SESSION['studentLoggedin']) && $_SESSION['studentLoggedin'] == true) {
    header("Location: /DE-Project/Student/StudentCredential/HomePage.php");
    exit();
}

include("../../_DBConnect.php");

$error = "";

// check form contains value
if (isset($_POST['login'])) {

    $email = $_POST['email'];

    if (!empty($email)) {

        $query = mysqli_query($conn, "SELECT * FROM facultydata WHERE email='$email'");

        if (mysqli_num_rows($query) > 0) {

            $otp = rand(100000, 999999);

            $_SESSION['otp'] = $otp;
            $_SESSION['email'] = $email;

            header("Location: ./LoginOTP.php");
            exit();

        } else {
            $error = "Email not found";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Login</title>

    <!-- Latest Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container d-flex align-items-center justify-content-center min-vh-100">

    <!-- LARGE CARD -->
    <div class="card shadow-lg border-0 rounded-4 w-100 col-lg-10 col-xl-9">

        <div class="row g-0">

            <!-- Image -->
            <div class="col-md-6 d-none d-md-block">
                <img src="../../Assets/Faculty/FacultyLogin.jpeg"
                     class="img-fluid h-100 w-100 rounded-start-4">
            </div>

            <!-- Form -->
            <div class="col-md-6">

                <div class="card-body p-5">

                    <h2 class="text-center fw-bold mb-4">
                        Faculty Login
                    </h2>

                    <?php
                    if ($error != "") {
                        echo "<div class='alert alert-danger text-center'>$error</div>";
                    }
                    ?>

                    <form method="post" action="./Login.php">

                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                Faculty Email
                            </label>
                            <input type="email"
                                name="email"
                                class="form-control form-control-lg rounded-pill"
                                placeholder="Enter your email"
                                required>
                        </div>

                        <button type="submit"
                            name="login"
                            class="btn btn-success w-100 rounded-pill">
                            Send OTP
                        </button>

                        <a href="../../index.php"
                           class="btn btn-outline-secondary w-100 mt-3 rounded-pill">
                            Back to Login Options
                        </a>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>