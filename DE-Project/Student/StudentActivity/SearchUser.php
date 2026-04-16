<?php
    session_start();
    include("../../_DBConnect.php");

    //**************get a value of searched input from _Navbar.php**************
    $search = $_GET['search'] ?? "";

    //**************removing space from both sides**************
    $search = trim($search);

    //**************used to escape special characters in a string to make it safe for use in SQL queries and prevent SQL injection.**************
    $search = mysqli_real_escape_string($conn, $search);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Result</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <?php include("../_Navbar.php"); ?>

    <div class="container py-5">

        <!--==========show the searched input from _Navbar.php==========-->
        <div class="mb-4">
            <h3 class="fw-bold">Search Results</h3>
            <p class="text-muted mb-0">
                Showing results for "<b><?php echo htmlspecialchars($search); ?></b>"
            </p>
        </div>

        <hr>

        <!--==========if the search is empty or have only empty space input==========-->
        <?php
            if (empty($search)) {
                echo "<div class='alert alert-warning'>Please enter something to search.</div>";
                exit();
            }

            /*************search student from database table*************/
            $sql1 = "SELECT * FROM studentdata WHERE enrollment LIKE '%$search%' OR name LIKE '%$search%' OR email LIKE '%$search%'";
            $r1 = mysqli_query($conn, $sql1);

            /*************search faculty from database table*************/
            $sql2 = "SELECT * FROM facultydata WHERE name LIKE '%$search%' OR email LIKE '%$search%' OR mobile LIKE '%$search%'";
            $r2 = mysqli_query($conn, $sql2);
        ?>

        <!--=================STUDENTS=================-->
        <h4 class="mt-4 mb-3 fw-semibold">Students</h4>

        <div class="row g-3">

            <!--=================check database table have data=================-->
            <?php if (mysqli_num_rows($r1) > 0) { ?>

                <!--=================fetch one by one row(data) from database table in an array formate=================-->
                <?php while ($row = mysqli_fetch_assoc($r1)) { ?>

                    <div class="col-12 col-md-6 col-lg-4">

                        <div class="card shadow-sm border-0 rounded-4 h-100">

                            <div class="card-body">

                                <h5 class="fw-semibold mb-2">
                                    
                                    <!--=================link to the SearchProfile.php for particular row(data) for student according to enrollment=================-->
                                    <a class="text-decoration-none" href="SearchProfile.php?id=<?php echo $row['enrollment']; ?>&type=student">

                                        <!--=================converts special characters into HTML entities=================-->
                                        <?php echo htmlspecialchars($row['name']); ?>

                                    </a>

                                </h5>

                                <p class="text-muted mb-0">
                                    <!--=================for displaying email from particular row data=================-->
                                    <?php echo htmlspecialchars($row['email']); ?>
                                </p>

                            </div>

                        </div>

                    </div>

                <?php } ?>

            <!--=================if database table have no data=================-->
            <?php } else { ?>
                <p class="text-muted">No students found</p>
            <?php } ?>

        </div>

        <!-- ================= FACULTY ================= -->
        <h4 class="mt-5 mb-3 fw-semibold">Faculty</h4>

        <div class="row g-3">
            <!--=================check database table have data=================-->
            <?php if (mysqli_num_rows($r2) > 0) { ?>

                <!--=================fetch one by one row(data) from database table in an array formate=================-->
                <?php while ($row = mysqli_fetch_assoc($r2)) { ?>

                    <div class="col-12 col-md-6 col-lg-4">

                        <div class="card shadow-sm border-0 rounded-4 h-100">

                            <div class="card-body">

                                <h5 class="fw-semibold mb-2">

                                    <!--=================link to the SearchProfile.php for particular row(data) for faculty according to email=================-->
                                    <a class="text-decoration-none" href="SearchProfile.php?id=<?php echo $row['email']; ?>&type=faculty">

                                        <!--=================converts special characters into HTML entities=================-->
                                        <?php echo htmlspecialchars($row['name']); ?>

                                    </a>

                                </h5>

                                <p class="text-muted mb-0">
                                    <!--=================for displaying email from particular row data=================-->
                                    <?php echo htmlspecialchars($row['email']); ?>
                                </p>

                            </div>

                        </div>

                    </div>

                <?php } ?>


            <!--=================if database table have no data=================-->
            <?php } else { ?>
                <p class="text-muted">No faculty found</p>
            <?php } ?>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>