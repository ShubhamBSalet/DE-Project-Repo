<?php
session_start();

// Redirect if already logged in
if (isset($_SESSION['studentLoggedin']) && $_SESSION['studentLoggedin'] == true) {
    header("Location: /DE-Project/Student/StudentCredential/HomePage.php");
    exit();
}

if (isset($_SESSION['facultyLoggedin']) && $_SESSION['facultyLoggedin'] == true) {
    header("Location: /DE-Project/Faculty/FacultyCredential/HomePage.php");
    exit();
}

$error = "";
if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
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

    <!-- Loader -->
    <div id="loader" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:white; z-index:9999; justify-content:center; align-items:center;">
        <div class="spinner-border text-primary" style="width:3rem; height:3rem;"></div>
    </div>

    <div class="container d-flex align-items-center justify-content-center min-vh-100">

        <div class="card shadow-lg border-0 rounded-4 w-100 col-lg-10 col-xl-9">

            <div class="row g-0">

                <div class="col-md-6 d-none d-md-block">
                    <img src="../../Assets/Student/StudentLogin.jpg" class="img-fluid h-100 w-100 rounded-start-4">
                </div>

                <div class="col-md-6">
                    <div class="card-body p-5">

                        <h2 class="text-center fw-bold mb-4">Student Login</h2>

                        <?php
                        if ($error != "") {
                            echo "<div class='alert alert-danger text-center'>$error</div>";
                        }
                        ?>

                            <form method="post" action="./LoginProcess.php" onsubmit="showLoader()">

                            <div class="mb-4">
                                <label class="form-label fw-semibold">Student Email</label>
                                <input type="email" name="email" class="form-control form-control-lg rounded-pill" placeholder="Enter your email" required>
                            </div>

                            <button type="submit" name="login" class="btn btn-primary w-100 rounded-pill">Send OTP</button>

                            <a href="../../index.php" class="btn btn-outline-secondary w-100 mt-3 rounded-pill">Back</a>

                        </form>

                    </div>
                </div>

            </div>

        </div>

    </div>

    <script>
        function showLoader() {
            document.getElementById("loader").style.display = "flex";
        }
    </script>

</body>

</html>