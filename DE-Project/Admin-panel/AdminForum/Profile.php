<?php
session_start();

/* ✅ FIXED PATH */
include("../../_DBConnect.php");

/* ✅ CHECK CONNECTION */
if (!$conn) {
    die("Database connection failed");
}

/* ================= GET ID ================= */
if (!isset($_GET['id'])) {
    die("Invalid Request");
}

$id = mysqli_real_escape_string($conn, $_GET['id']);

/* ================= CHECK STUDENT ================= */
$query = "SELECT * FROM studentdata WHERE enrollment='$id'";
$q = mysqli_query($conn, $query);

if (mysqli_num_rows($q) > 0) {

    $row = mysqli_fetch_assoc($q);
    $type = "student";
} else {

    /* ================= CHECK FACULTY ================= */
    $query = "SELECT * FROM facultydata WHERE email='$id'";
    $q = mysqli_query($conn, $query);

    if (mysqli_num_rows($q) > 0) {

        $row = mysqli_fetch_assoc($q);
        $type = "faculty";
    } else {
        die("User not found");
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?php echo ($type == "student") ? "Student Profile" : "Faculty Profile"; ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <?php include("../_Navbar.php"); ?>

    <div class="container py-5">

        <div class="row justify-content-center">

            <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5">

                <div class="card shadow border-0 rounded-4">

                    <!-- HEADER -->
                    <div class="card-header text-white text-center rounded-top-4 
                    <?php echo ($type == "student") ? "bg-primary" : "bg-success"; ?>">

                        <h4 class="mb-0 fw-bold">
                            <?php echo ($type == "student") ? "Student Profile" : "Faculty Profile"; ?>
                        </h4>

                    </div>

                    <!-- BODY -->
                    <div class="card-body text-center p-4">

                        <!-- AVATAR -->
                        <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png"
                            class="rounded-circle img-fluid mb-3"
                            style="width:120px;">

                        <!-- NAME -->
                        <h4 class="fw-bold mb-2">
                            <?php echo htmlspecialchars($row['name']); ?>
                        </h4>

                        <!-- ROLE -->
                        <span class="badge mb-3 
                        <?php echo ($type == "student") ? "bg-primary" : "bg-success"; ?>">
                            <?php echo ucfirst($type); ?>
                        </span>

                        <hr>

                        <!-- DETAILS -->
                        <div class="text-start">

                            <?php if ($type == "student") { ?>

                                <p><strong>Enrollment:</strong> <?php echo $row['enrollment']; ?></p>
                                <p><strong>Email:</strong> <?php echo $row['email']; ?></p>

                            <?php } ?>

                            <?php if ($type == "faculty") { ?>

                                <p><strong>Email:</strong> <?php echo $row['email']; ?></p>
                                <p><strong>Post:</strong> <?php echo $row['post']; ?></p>

                            <?php } ?>

                            <p><strong>Branch:</strong> <?php echo $row['branch']; ?></p>
                            <p><strong>Mobile:</strong> <?php echo $row['mobile']; ?></p>

                        </div>

                    </div>

                    <!-- FOOTER -->
                    <div class="card-footer bg-transparent text-center border-0 pb-4">

                        <a href="./AdminForum.php"
                            class="btn btn-outline-dark rounded-pill px-4">
                            Back
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</body>

</html>