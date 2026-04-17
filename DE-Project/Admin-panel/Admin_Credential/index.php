<?php
session_start();

// If admin already logged in
if (isset($_SESSION['AdminLoggedin']) && $_SESSION['AdminLoggedin'] == true) {
    header("Location: ./HomePage.php");
    exit();
}

$error = "";
if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
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

    <!-- Loader -->
    <div id="loader" style="display:none; position:fixed; width:100%; height:100%; background:white; z-index:9999; justify-content:center; align-items:center;">
        <div class="spinner-border text-dark" style="width:3rem; height:3rem;"></div>
    </div>

    <div class="container d-flex justify-content-center align-items-center vh-100">

        <div class="card shadow-lg w-100" style="max-width:900px;">
            <div class="row g-0">

                <div class="col-md-6 d-none d-md-block">
                    <img src="../../Assets/Admin/AdminLogin.png" class="img-fluid h-100 w-100" style="object-fit:cover;">
                </div>

                <div class="col-md-6">
                    <div class="card-body p-4">

                        <h3 class="mb-4 text-center">Admin Login</h3>

                        <?php if ($error != "") echo "<div class='alert alert-danger'>$error</div>"; ?>

                        <form method="post" action="./LoginProcess.php" onsubmit="showLoader()">

                            <div class="mb-3">
                                <label class="form-label">Admin Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>

                            <button type="submit" name="login" class="btn btn-dark w-100">Send OTP</button>

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