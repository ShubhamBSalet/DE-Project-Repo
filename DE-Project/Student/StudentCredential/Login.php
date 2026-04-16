<?php
    session_start();

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

    include("../../_DBConnect.php");

    $error = "";

    //*****************check form contains value*****************
    if (isset($_POST['login'])) {

        // store email from form input
        $email = $_POST['email'];

        if (!empty($email)) {

            // check email already exists in database
            $query = "SELECT * FROM studentdata WHERE email='$email'";
            $q = mysqli_query($conn, $query);

            if (mysqli_num_rows($q) > 0) {

                // generate 6 digit otp
                $otp = rand(100000, 999999);

                // create session variables for other page
                $_SESSION['otp'] = $otp;
                $_SESSION['email'] = $email;

                // redirect to login otp
                header("Location: ./LoginOTP.php");
                exit();
            } 
            else {
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
    <title>Student Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container d-flex align-items-center justify-content-center min-vh-100">

        <div class="card shadow-lg border-0 rounded-4 w-100 col-lg-10 col-xl-9">

            <div class="row g-0">

                <div class="col-md-6 d-none d-md-block">
                    <img src="../../Assets/Student/StudentLogin.jpg" class="img-fluid h-100 w-100 rounded-start-4">
                </div>

                <div class="col-md-6">

                    <div class="card-body p-5">

                        <h2 class="text-center fw-bold mb-4">
                            Student Login
                        </h2>

                        <!--===============show error message===============-->
                        <?php
                        if ($error != "") {
                            echo "<div class='alert alert-danger text-center'>$error</div>";
                        }
                        ?>

                        <form method="post" action="./Login.php">

                            <div class="mb-4">
                                <label class="form-label fw-semibold">Student Email</label>
                                <input type="email" name="email" class="form-control form-control-lg rounded-pill" placeholder="Enter your email" required>
                            </div>

                            <button type="submit" name="login" class="btn btn-primary w-100 rounded-pill">Send OTP</button>

                            <a href="../../index.php" class="btn btn-outline-secondary w-100 mt-3 rounded-pill">Back to Login Options </a>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>