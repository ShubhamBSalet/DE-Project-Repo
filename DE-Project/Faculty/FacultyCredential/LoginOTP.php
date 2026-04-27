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

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-body-tertiary">

<div class="container d-flex justify-content-center align-items-center min-vh-100">

    <div class="col-md-5 col-lg-4">

        <div class="card shadow border-0 rounded-4">

            <div class="card-body p-5 text-center">

                <!-- Icon -->
                <div class="mb-3">
                    <i class="bi bi-shield-lock-fill text-success" style="font-size: 2.5rem;"></i>
                </div>

                <!-- Title -->
                <h4 class="fw-bold mb-2">Faculty Verification</h4>

                <!-- Subtitle -->
                <p class="text-muted mb-4">
                    Enter the 6-digit OTP sent to your email
                </p>

                <!-- Error -->
                <?php if ($error != "") { ?>
                    <div class="alert alert-danger py-2">
                        <?php echo $error; ?>
                    </div>
                <?php } ?>

                <!-- Form -->
                <form method="post">

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
                            class="btn btn-success w-100 py-2 rounded-pill">
                        <i class="bi bi-check-circle me-1"></i> Verify OTP
                    </button>

                </form>

                <!-- Help Text -->
                <p class="text-muted mt-4 small mb-0">
                    Didn’t receive OTP? Check spam folder.
                </p>

            </div>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>