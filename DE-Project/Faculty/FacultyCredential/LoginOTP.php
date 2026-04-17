<?php
session_start();
include("../../_DBConnect.php");

// Already logged in
if (isset($_SESSION['facultyLoggedin']) && $_SESSION['facultyLoggedin'] == true) {
    header("Location: /DE-Project/Faculty/FacultyCredential/HomePage.php");
    exit();
}

if (isset($_SESSION['studentLoggedin']) && $_SESSION['studentLoggedin'] == true) {
    header("Location: /DE-Project/Student/StudentCredential/HomePage.php");
    exit();
}

$error = "";

// No OTP session
if (!isset($_SESSION['otp'])) {
    header("Location: Login.php");
    exit();
}

// Verify OTP
if (isset($_POST['verifyOTP'])) {

    $userOtp = $_POST['otp'];

    if ($userOtp == $_SESSION['otp']) {

        $_SESSION['facultyLoggedin'] = true;

        $email = $_SESSION['email'];

        // Get faculty info (optional)
        $query = "SELECT * FROM facultydata WHERE email='$email'";
        $q = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($q);

        $_SESSION['faculty_id'] = $row['id']; // optional

        unset($_SESSION['otp']);

        header("Location: /DE-Project/Faculty/FacultyCredential/HomePage.php");
        exit();
    } else {
        $error = "Invalid OTP";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Faculty OTP Verification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container d-flex justify-content-center align-items-center min-vh-100">

        <div class="card p-5 shadow rounded-4 text-center">

            <h3>OTP Verification</h3>
            <p class="text-muted">Enter OTP sent to email</p>

            <?php if ($error != "") echo "<div class='alert alert-danger'>$error</div>"; ?>

            <form method="post">

                <input type="text" name="otp" class="form-control mb-3 text-center" maxlength="6" required>

                <button name="verifyOTP" class="btn btn-success w-100">Verify OTP</button>

            </form>

        </div>

    </div>

</body>

</html>