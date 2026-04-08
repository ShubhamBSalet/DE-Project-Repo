<?php
session_start();
include("../../_DBConnect.php");

$id = $_GET['id'];
$type = $_GET['type'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>

    <!-- Latest Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <?php include("../_Navbar.php"); ?>

    <div class="container py-5">

        <div class="row justify-content-center">

            <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5">

                <?php

                // ================= STUDENT =================
                if ($type == "student") {
                    $sql = "SELECT * FROM studentdata WHERE enrollment='$id'";
                    $result = mysqli_query($conn, $sql);
                    $row = mysqli_fetch_assoc($result);
                ?>

                    <div class="card shadow-lg border-0 rounded-4">

                        <!-- Header -->
                        <div class="card-header bg-primary text-white text-center rounded-top-4">
                            <h4 class="mb-0 fw-semibold">Student Profile</h4>
                        </div>

                        <!-- Body -->
                        <div class="card-body p-5 text-center">

                            <h3 class="fw-bold text-uppercase mb-3">
                                <?php echo $row['name']; ?>
                            </h3>

                            <span class="badge bg-primary mb-4">
                                <?php echo ucfirst($type); ?>
                            </span>

                            <div class="text-start">

                                <p class="mb-2">
                                    <span class="fw-semibold">Enrollment:</span>
                                    <?php echo $row['enrollment']; ?>
                                </p>

                                <p class="mb-2">
                                    <span class="fw-semibold">Email:</span>
                                    <?php echo $row['email']; ?>
                                </p>

                                <p class="mb-2">
                                    <span class="fw-semibold">Branch:</span>
                                    <?php echo $row['branch']; ?>
                                </p>

                                <p class="mb-0">
                                    <span class="fw-semibold">Mobile:</span>
                                    <?php echo $row['mobile']; ?>
                                </p>


                                <?php
                                // detect current user
                                $currentUser = isset($_SESSION['enrollment'])
                                    ? $_SESSION['enrollment']
                                    : $_SESSION['email'];

                                if ($currentUser != $id) {
                                ?>
                                    <a href="/DE-PROJECT/Student/Student_Message/chat.php?receiver_id=<?php echo $id; ?>&type=<?php echo $type; ?>"
                                        class="btn btn-primary mt-3">
                                        Message
                                    </a>
                                <?php } ?>

                            </div>

                        </div>

                    </div>

                <?php
                }

                // ================= FACULTY =================
                if ($type == "faculty") {
                    $sql = "SELECT * FROM facultydata WHERE email='$id'";
                    $result = mysqli_query($conn, $sql);
                    $row = mysqli_fetch_assoc($result);
                ?>

                    <div class="card shadow-lg border-0 rounded-4">

                        <!-- Header -->
                        <div class="card-header bg-success text-white text-center rounded-top-4">
                            <h4 class="mb-0 fw-semibold">Faculty Profile</h4>
                        </div>

                        <!-- Body -->
                        <div class="card-body p-5 text-center">

                            <h3 class="fw-bold text-uppercase mb-3">
                                <?php echo $row['name']; ?>
                            </h3>

                            <span class="badge bg-success mb-4">
                                <?php echo ucfirst($type); ?>
                            </span>

                            <div class="text-start">

                                <p class="mb-2">
                                    <span class="fw-semibold">Email:</span>
                                    <?php echo $row['email']; ?>
                                </p>

                                <p class="mb-2">
                                    <span class="fw-semibold">Post:</span>
                                    <?php echo $row['post']; ?>
                                </p>

                                <p class="mb-2">
                                    <span class="fw-semibold">Branch:</span>
                                    <?php echo $row['branch']; ?>
                                </p>

                                <p class="mb-0">
                                    <span class="fw-semibold">Mobile:</span>
                                    <?php echo $row['mobile']; ?>
                                </p>

                                <?php
                                $currentUser = isset($_SESSION['enrollment'])
                                    ? $_SESSION['enrollment']
                                    : $_SESSION['email'];

                                if ($currentUser != $id) {
                                ?>
                                    <a href="/DE-PROJECT/Student/Student_Message/chat.php?receiver_id=<?php echo $id; ?>&type=<?php echo $type; ?>"
                                        class="btn btn-success mt-3">
                                        Message
                                    </a>
                                <?php } ?>


                            </div>

                        </div>

                    </div>

                <?php } ?>

            </div>

        </div>

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>