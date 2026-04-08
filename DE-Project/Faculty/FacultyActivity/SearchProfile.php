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

<body class="bg-light">

<?php include("../_Navbar.php"); ?>

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5">

            <?php

            /* ================= STUDENT ================= */
            if ($type == "student") {

                $sql = "SELECT * FROM studentdata WHERE enrollment='$id'";
                $result = mysqli_query($conn, $sql);
                $row = mysqli_fetch_assoc($result);
            ?>

                <div class="card shadow-lg border-0 rounded-4">

                    <div class="card-header bg-primary text-white text-center rounded-top-4">
                        <h4 class="mb-0 fw-semibold">Student Profile</h4>
                    </div>

                    <div class="card-body p-5 text-center">

                        <h3 class="fw-bold text-uppercase mb-3">
                            <?php echo $row['name']; ?>
                        </h3>

                        <span class="badge bg-primary mb-4">
                            Student
                        </span>

                        <div class="text-start">

                            <p><b>Enrollment:</b> <?php echo $row['enrollment']; ?></p>
                            <p><b>Email:</b> <?php echo $row['email']; ?></p>
                            <p><b>Branch:</b> <?php echo $row['branch']; ?></p>
                            <p><b>Mobile:</b> <?php echo $row['mobile']; ?></p>

                            <!-- MESSAGE BUTTON -->
                            <?php if ($currentUser != $id) { ?>
                                <a href="<?php echo $chatPath; ?>?receiver_id=<?php echo $id; ?>&type=<?php echo $type; ?>"
                                   class="btn btn-primary mt-3">
                                   Message
                                </a>
                            <?php } ?>

                        </div>

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

                <div class="card shadow-lg border-0 rounded-4">

                    <div class="card-header bg-success text-white text-center rounded-top-4">
                        <h4 class="mb-0 fw-semibold">Faculty Profile</h4>
                    </div>

                    <div class="card-body p-5 text-center">

                        <h3 class="fw-bold text-uppercase mb-3">
                            <?php echo $row['name']; ?>
                        </h3>

                        <span class="badge bg-success mb-4">
                            Faculty
                        </span>

                        <div class="text-start">

                            <p><b>Email:</b> <?php echo $row['email']; ?></p>
                            <p><b>Post:</b> <?php echo $row['post']; ?></p>
                            <p><b>Branch:</b> <?php echo $row['branch']; ?></p>
                            <p><b>Mobile:</b> <?php echo $row['mobile']; ?></p>

                            <!-- MESSAGE BUTTON -->
                            <?php if ($currentUser != $id) { ?>
                                <a href="<?php echo $chatPath; ?>?receiver_id=<?php echo $id; ?>&type=<?php echo $type; ?>"
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>