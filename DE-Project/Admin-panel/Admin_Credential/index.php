<?php
session_start();

//****************if faculty logged in****************
if (isset($_SESSION['AdminLoggedin']) && $_SESSION['AdminLoggedin'] == true) {
    header("Location: ./HomePage.php");
    exit();
}


include("../../_DBConnect.php");

$error = "";

// check form contains value
if (isset($_POST['login'])) {

    // get form input email
    $email = $_POST['email'];

    if (!empty($email)) {

        $query = mysqli_query($conn, "SELECT * FROM admindata WHERE email='$email'");

        // check entered email id exists in database table
        if (mysqli_num_rows($query) > 0) {

            // generate 6 digit random OTP
            $otp = rand(100000, 999999);

            // creating session variable for -> OTP & email
            $_SESSION['otp'] = $otp;
            $_SESSION['email'] = $email;

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
<html>

<head>
    <title>Admin - Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container d-flex justify-content-center align-items-center vh-100">

        <div class="card shadow-lg w-100" style="max-width:900px;">
            <div class="row g-0">


                <div class="col-md-6 d-none d-md-block">
                    <img src="../../Assets/Admin/AdminLogin.png" class="img-fluid h-100 w-100" style="object-fit:cover;">
                </div>

                <div class="col-md-6">
                    <div class="card-body p-4">
                        <h3 class="mb-4 text-center">Admin Login</h3>

                        <?php
                        if ($error != "") {
                            echo "<div class='alert alert-danger'>$error</div>";
                        }
                        ?>

                        <form method="post" action="./index.php">
                            <div class="mb-3">
                                <label class="form-label">Admin Email</label>
                                <input type="email" name="email" class="form-control" placeholder="Enter Email" required title="Enter valid email address">
                            </div>

                            <button type="submit" name="login" class="btn btn-dark w-100">Login</button>

                        </form>

                    </div>
                </div>

            </div>
        </div>

    </div>

</body>

</html>