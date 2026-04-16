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
    <!--==========show title according to type==========-->
    <title><?php echo ($type == "student") ? "Student Profile" : "Faculty Profile"; ?></title>

    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <?php include("../../_Navbar.php"); ?>

    <div class="container py-5">

        <div class="row justify-content-center">

            <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5">

                <div class="card shadow-sm border-0 rounded-4">

                    <!--==========show card header colour according to type==========-->
                    <div class="card-header text-white text-center rounded-top-4 <?php echo ($type == "student") ? "bg-primary" : "bg-success"; ?>">

                        <!--==========show card header title according to type==========-->
                        <h4 class="mb-0 fw-semibold">
                            <?php echo ($type == "student") ? "Student Profile" : "Faculty Profile"; ?>
                        </h4>

                    </div>

                    <div class="card-body text-center p-5">

                        <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" class="rounded-circle img-fluid w-50 mb-3">

                        <h4 class="fw-bold mb-2"><?php echo $row['name']; ?></h4>

                        <span class="badge mb-3 
                        <?php echo ($type == "student") ? "bg-primary" : "bg-success"; ?>">
                            <?php echo ucfirst($type); ?>
                        </span>

                        <hr>

                        <div class="text-start">

                            <?php if ($type == "student") { ?>

                                <p class="mb-2">
                                    <span class="fw-semibold">Enrollment:</span>
                                    <?php echo $row['enrollment']; ?>
                                </p>

                                <p class="mb-2">
                                    <span class="fw-semibold">Email:</span>
                                    <?php echo $row['email']; ?>
                                </p>

                            <?php } ?>

                            <?php if ($type == "faculty") { ?>

                                <p class="mb-2">
                                    <span class="fw-semibold">Email:</span>
                                    <?php echo $row['email']; ?>
                                </p>

                                <p class="mb-2">
                                    <span class="fw-semibold">Post:</span>
                                    <?php echo $row['post']; ?>
                                </p>

                            <?php } ?>

                            <p class="mb-2">
                                <span class="fw-semibold">Branch:</span>
                                <?php echo $row['branch']; ?>
                            </p>

                            <p class="mb-0">
                                <span class="fw-semibold">Mobile:</span>
                                <?php echo $row['mobile']; ?>
                            </p>

                        </div>

                    </div>

                    <div class="card-footer bg-transparent text-center border-0 pb-4">

                        <a href="StudentForum.php"
                            class="btn btn-outline-dark rounded-pill px-4">
                            Back
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>