<?php
session_start();
// if student logged in
if (isset($_SESSION['studentLoggedin']) && $_SESSION['studentLoggedin'] == true) {
    header("Location: ./Student/StudentCredential/HomePage.php");
    exit();
}

// if faculty logged in
if (isset($_SESSION['facultyLoggedin']) && $_SESSION['facultyLoggedin'] == true) {
    header("Location: ./Faculty/FacultyCredential/HomePage.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Linkademy - Campus Connected</title>

    <!-- Latest Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light d-flex align-items-center" style="min-height:100vh;">

    <div class="container text-center">

        <!-- Title -->
        <div class="mb-5">
            <h1 class="fw-bold">LinkAdemy</h1>
            <p class="text-muted fs-5">Campus Connected - Academic Social Platform</p>
        </div>

        <!-- Cards -->
        <div class="row justify-content-center g-4">

            <!-- Student -->
            <div class="col-12 col-sm-10 col-md-6 col-lg-5 col-xl-4">
                <div class="card shadow border-0 rounded-4 h-100">

                    <div class="p-4">
                        <img src="./Assets/Student/StudentLoginOption.jpg"
                            class="img-fluid"
                            style="max-height:220px; object-fit:contain;">
                    </div>

                    <div class="card-body">
                        <h4 class="fw-semibold">Student Login</h4>
                        <a href="./Student/StudentCredential/Login.php"
                            class="btn btn-primary w-100 mt-3 rounded-pill">
                            Login as Student
                        </a>
                    </div>

                </div>
            </div>

            <!-- Faculty -->
            <div class="col-12 col-sm-10 col-md-6 col-lg-5 col-xl-4">
                <div class="card shadow border-0 rounded-4 h-100">

                    <div class="p-4">
                        <img src="./Assets/Faculty/FacultyLoginOption.jpg"
                            class="img-fluid"
                            style="max-height:220px; object-fit:contain;">
                    </div>

                    <div class="card-body">
                        <h4 class="fw-semibold">Faculty Login</h4>
                        <a href="./Faculty/FacultyCredential/Login.php"
                            class="btn btn-success w-100 mt-3 rounded-pill">
                            Login as Faculty
                        </a>
                    </div>

                </div>
            </div>

        </div>

    </div>

    <!-- Bootstrap JS (optional but recommended) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>