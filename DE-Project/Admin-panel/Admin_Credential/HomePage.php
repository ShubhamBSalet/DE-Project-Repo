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

<body>

    <?php include("../_Navbar.php"); ?>

    <div class="container mt-5">

        <div class="row justify-content-center">

            <div class="col-md-6">

                <div class="card shadow-lg">

                    <div class="card-header bg-dark text-white text-center">
                        <h4>Admin Profile</h4>
                    </div>


                    <div class="card-body text-center">

                        <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" width="120" class="mb-3">
                        <h4><?php echo htmlspecialchars($row['name']); ?></h4>

                        <p>
                            <b>Email:</b>
                            <?php echo htmlspecialchars($row['email']); ?>
                        </p>

                        <p>
                            <b>Branch:</b>
                            <?php echo htmlspecialchars($row['branch']); ?>
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>