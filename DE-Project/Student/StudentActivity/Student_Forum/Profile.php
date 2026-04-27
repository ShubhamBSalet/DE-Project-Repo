<?php
    session_start();
    include("../../../_DBConnect.php");

    //************get id from StudentForum.php (student->enrollment | faculty->email)************
    $id = $_GET['id'];

    //************fetch student data from the database table according to enrollment************
    $query = "SELECT * FROM studentdata WHERE enrollment='$id'";
    $q = mysqli_query($conn, $query);

    //************fetch row by row data from database table************
    if (mysqli_num_rows($q) > 0) {
        //************store row(data) as an array format************
        $row = mysqli_fetch_assoc($q);
        $type = "student";
    } 
    else {
        //************fetch faculty data from the database table according to email************
        $query = "SELECT * FROM facultydata WHERE email='$id'";
        $q = mysqli_query($conn, $query);

        //************fetch row by row data from database table************
        if (mysqli_num_rows($q) > 0) {
            //************store row(data) as an array format************
            $row = mysqli_fetch_assoc($q);
            $type = "faculty";
        } 
        else {
            die("User not found");
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?php echo ($type == "student") ? "Student Profile" : "Faculty Profile"; ?></title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-body-tertiary">

<?php include("../../_Navbar.php"); ?>

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-lg-6 col-md-8">

            <div class="card shadow border-0 rounded-4 overflow-hidden">

                <!-- Top Banner -->
                <div class="<?php echo ($type == "student") ? "bg-primary" : "bg-success"; ?>" style="height: 100px;"></div>

                <!-- Profile Section -->
                <div class="text-center px-4 pb-4">

                    <!-- Avatar -->
                    <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png"
                         class="rounded-circle shadow"
                         width="100"
                         style="margin-top: -50px;">

                    <!-- Name -->
                    <h4 class="fw-bold mt-3 mb-1"><?php echo $row['name']; ?></h4>

                    <!-- Role Badge -->
                    <span class="badge <?php echo ($type == "student") ? "bg-primary" : "bg-success"; ?>">
                        <?php echo ucfirst($type); ?>
                    </span>

                </div>

                <!-- Info Section -->
                <div class="px-4 pb-4">

                    <div class="list-group list-group-flush">

                        <?php if ($type == "student") { ?>

                            <div class="list-group-item d-flex justify-content-between">
                                <span><i class="bi bi-person-badge me-2"></i>Enrollment</span>
                                <span class="fw-semibold"><?php echo $row['enrollment']; ?></span>
                            </div>

                            <div class="list-group-item d-flex justify-content-between">
                                <span><i class="bi bi-envelope me-2"></i>Email</span>
                                <span class="fw-semibold"><?php echo $row['email']; ?></span>
                            </div>

                        <?php } ?>

                        <?php if ($type == "faculty") { ?>

                            <div class="list-group-item d-flex justify-content-between">
                                <span><i class="bi bi-envelope me-2"></i>Email</span>
                                <span class="fw-semibold"><?php echo $row['email']; ?></span>
                            </div>

                            <div class="list-group-item d-flex justify-content-between">
                                <span><i class="bi bi-briefcase me-2"></i>Post</span>
                                <span class="fw-semibold"><?php echo $row['post']; ?></span>
                            </div>

                        <?php } ?>

                        <div class="list-group-item d-flex justify-content-between">
                            <span><i class="bi bi-diagram-3 me-2"></i>Branch</span>
                            <span class="fw-semibold"><?php echo $row['branch']; ?></span>
                        </div>

                        <div class="list-group-item d-flex justify-content-between">
                            <span><i class="bi bi-phone me-2"></i>Mobile</span>
                            <span class="fw-semibold"><?php echo $row['mobile']; ?></span>
                        </div>

                    </div>

                </div>

                <!-- Footer -->
                <div class="text-center pb-4">

                    <a href="StudentForum.php"
                       class="btn btn-outline-dark rounded-pill px-4">
                        <i class="bi bi-arrow-left me-1"></i> Back
                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>