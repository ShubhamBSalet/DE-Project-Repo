<?php
session_start();

// ✅ Check if admin NOT logged in → redirect
if (!isset($_SESSION['AdminLoggedin']) || $_SESSION['AdminLoggedin'] != true) {
    header("Location: ../_NotLoggedIn.php");
    exit();
}

include("../../_DBConnect.php");

$id = $_SESSION['email'];

$query = "SELECT * FROM admindata WHERE email='$id'";
$q = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($q);
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-body-tertiary">

<?php include("../_Navbar.php"); ?>

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-lg-5 col-md-7">

            <div class="card shadow border-0 rounded-4 overflow-hidden">

                <!-- HEADER -->
                <div class="bg-dark text-white text-center py-4">

                    <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png"
                         class="rounded-circle border border-3 border-white mb-2"
                         width="100">

                    <h4 class="fw-bold mb-0">
                        <?php echo htmlspecialchars($row['name']); ?>
                    </h4>

                    <span class="badge bg-light text-dark mt-2 px-3 py-2">
                        Admin
                    </span>

                </div>

                <!-- BODY -->
                <div class="card-body p-4">

                    <div class="row mb-3">
                        <div class="col-4 fw-semibold text-muted">
                            Email
                        </div>
                        <div class="col-8">
                            <?php echo htmlspecialchars($row['email']); ?>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-4 fw-semibold text-muted">
                            Branch
                        </div>
                        <div class="col-8">
                            <?php echo htmlspecialchars($row['branch']); ?>
                        </div>
                    </div>

                </div>


            </div>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>