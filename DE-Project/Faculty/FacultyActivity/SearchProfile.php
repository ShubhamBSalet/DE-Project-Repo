<?php
session_start();
include("../../_DBConnect.php");

$id = $_GET['id'];
$type = $_GET['type'];

// detect current user
$currentUser = isset($_SESSION['enrollment'])
    ? $_SESSION['enrollment']
    : $_SESSION['email'];

// detect chat path
if (isset($_SESSION['facultyLoggedin'])) {
    $chatPath = "/DE-Project/Faculty/Faculty_Message/chat.php";
} else {
    $chatPath = "/DE-Project/Student/Student_Message/chat.php";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-body-tertiary">

<?php include("../_Navbar.php"); ?>

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-lg-5 col-md-8">

            <?php

            /* ================= STUDENT ================= */
            if ($type == "student") {

                $sql = "SELECT * FROM studentdata WHERE enrollment='$id'";
                $result = mysqli_query($conn, $sql);
                $row = mysqli_fetch_assoc($result);
            ?>

                <div class="card shadow border-0 rounded-4 overflow-hidden">

                    <!-- HEADER -->
                    <div class="bg-primary text-white text-center py-4">
                        <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png"
                             class="rounded-circle border border-3 border-white mb-2"
                             width="90">

                        <h4 class="fw-bold mb-0"><?php echo $row['name']; ?></h4>

                        <span class="badge bg-light text-primary mt-2 px-3 py-2">
                            Student
                        </span>
                    </div>

                    <!-- BODY -->
                    <div class="card-body p-4">

                        <div class="row mb-2">
                            <div class="col-5 fw-semibold">Enrollment</div>
                            <div class="col-7"><?php echo $row['enrollment']; ?></div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-5 fw-semibold">Email</div>
                            <div class="col-7"><?php echo $row['email']; ?></div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-5 fw-semibold">Branch</div>
                            <div class="col-7"><?php echo $row['branch']; ?></div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-5 fw-semibold">Mobile</div>
                            <div class="col-7"><?php echo $row['mobile']; ?></div>
                        </div>

                        <!-- BUTTON -->
                        <?php if ($currentUser != $id) { ?>
                            <a href="<?php echo $chatPath; ?>?receiver_id=<?php echo $id; ?>&type=<?php echo $type; ?>"
                               class="btn btn-primary w-100 rounded-pill">
                               💬 Message
                            </a>
                        <?php } ?>

                    </div>

                </div>

            <?php
            }

            /* ================= FACULTY ================= */
            if ($type == "faculty") {

                $sql = "SELECT * FROM facultydata WHERE email='$id'";
                $result = mysqli_query($conn, $sql);
                $row = mysqli_fetch_assoc($result);
            ?>

                <div class="card shadow border-0 rounded-4 overflow-hidden">

                    <!-- HEADER -->
                    <div class="bg-success text-white text-center py-4">
                        <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png"
                             class="rounded-circle border border-3 border-white mb-2"
                             width="90">

                        <h4 class="fw-bold mb-0"><?php echo $row['name']; ?></h4>

                        <span class="badge bg-light text-success mt-2 px-3 py-2">
                            Faculty
                        </span>
                    </div>

                    <!-- BODY -->
                    <div class="card-body p-4">

                        <div class="row mb-2">
                            <div class="col-5 fw-semibold">Email</div>
                            <div class="col-7"><?php echo $row['email']; ?></div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-5 fw-semibold">Post</div>
                            <div class="col-7"><?php echo $row['post']; ?></div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-5 fw-semibold">Branch</div>
                            <div class="col-7"><?php echo $row['branch']; ?></div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-5 fw-semibold">Mobile</div>
                            <div class="col-7"><?php echo $row['mobile']; ?></div>
                        </div>

                        <!-- BUTTON -->
                        <?php if ($currentUser != $id) { ?>
                            <a href="<?php echo $chatPath; ?>?receiver_id=<?php echo $id; ?>&type=<?php echo $type; ?>"
                               class="btn btn-success w-100 rounded-pill">
                               💬 Message
                            </a>
                        <?php } ?>

                    </div>

                </div>

            <?php } ?>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>