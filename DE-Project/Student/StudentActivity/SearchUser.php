<?php
session_start();
include("../../_DBConnect.php");

// Get search safely
$search = $_GET['search'] ?? "";
$search = trim($search);

// ✅ Prevent SQL error (fix for sam')
$search = mysqli_real_escape_string($conn, $search);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Result</title>

    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<?php include("../_Navbar.php"); ?>

<div class="container py-5">

    <!-- Title -->
    <div class="mb-4">
        <h3 class="fw-bold">Search Results</h3>
        <p class="text-muted mb-0">
            Showing results for "<b><?php echo htmlspecialchars($search); ?></b>"
        </p>
    </div>

    <hr>

    <?php
    // 🚫 Empty search check
    if (empty($search)) {
        echo "<div class='alert alert-warning'>Please enter something to search.</div>";
        exit();
    }

    /* ===== search student ===== */
    $sql1 = "SELECT * FROM studentdata 
    WHERE enrollment LIKE '%$search%' 
    OR name LIKE '%$search%' 
    OR email LIKE '%$search%'";

    $r1 = mysqli_query($conn, $sql1);

    /* ===== search faculty ===== */
    $sql2 = "SELECT * FROM facultydata 
    WHERE name LIKE '%$search%' 
    OR email LIKE '%$search%' 
    OR mobile LIKE '%$search%'";

    $r2 = mysqli_query($conn, $sql2);
    ?>

    <!-- ================= STUDENTS ================= -->
    <h4 class="mt-4 mb-3 fw-semibold">Students</h4>

    <div class="row g-3">

        <?php if (mysqli_num_rows($r1) > 0) { ?>

            <?php while ($row = mysqli_fetch_assoc($r1)) { ?>

                <div class="col-12 col-md-6 col-lg-4">

                    <div class="card shadow-sm border-0 rounded-4 h-100">

                        <div class="card-body">

                            <h5 class="fw-semibold mb-2">
                                <a class="text-decoration-none"
                                   href="SearchProfile.php?id=<?php echo $row['enrollment']; ?>&type=student">

                                    <?php echo htmlspecialchars($row['name']); ?>

                                </a>
                            </h5>

                            <p class="text-muted mb-0">
                                <?php echo htmlspecialchars($row['email']); ?>
                            </p>

                        </div>

                    </div>

                </div>

            <?php } ?>

        <?php } else { ?>

            <p class="text-muted">No students found</p>

        <?php } ?>

    </div>

    <!-- ================= FACULTY ================= -->
    <h4 class="mt-5 mb-3 fw-semibold">Faculty</h4>

    <div class="row g-3">

        <?php if (mysqli_num_rows($r2) > 0) { ?>

            <?php while ($row = mysqli_fetch_assoc($r2)) { ?>

                <div class="col-12 col-md-6 col-lg-4">

                    <div class="card shadow-sm border-0 rounded-4 h-100">

                        <div class="card-body">

                            <h5 class="fw-semibold mb-2">
                                <a class="text-decoration-none"
                                   href="SearchProfile.php?id=<?php echo $row['email']; ?>&type=faculty">

                                    <?php echo htmlspecialchars($row['name']); ?>

                                </a>
                            </h5>

                            <p class="text-muted mb-0">
                                <?php echo htmlspecialchars($row['email']); ?>
                            </p>

                        </div>

                    </div>

                </div>

            <?php } ?>

        <?php } else { ?>

            <p class="text-muted">No faculty found</p>

        <?php } ?>

    </div>

</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>