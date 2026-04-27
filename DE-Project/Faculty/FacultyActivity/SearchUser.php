<?php
session_start();
include("../../_DBConnect.php");

// Get search input
$search = $_GET['search'] ?? "";
$search = trim($search);
$search = mysqli_real_escape_string($conn, $search);

// ✅ Always define variables
$r1 = false;
$r2 = false;

// Run query only if search is not empty
if (!empty($search)) {

    // Students
    $sql1 = "SELECT * FROM studentdata 
             WHERE enrollment LIKE '%$search%' 
             OR name LIKE '%$search%' 
             OR email LIKE '%$search%'";

    $r1 = mysqli_query($conn, $sql1);

    // Faculty
    $sql2 = "SELECT * FROM facultydata 
             WHERE name LIKE '%$search%' 
             OR email LIKE '%$search%' 
             OR mobile LIKE '%$search%'";

    $r2 = mysqli_query($conn, $sql2);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Search Result</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-body-tertiary">

<?php include("../_Navbar.php"); ?>

<div class="container py-5">

    <!-- HEADER -->
    <div class="card shadow border-0 rounded-4 p-4 mb-4">

        <h4 class="fw-bold mb-1">
            <i class="bi bi-search me-2"></i> Search Results
        </h4>

        <p class="text-muted mb-0">
            Showing results for "<b><?php echo htmlspecialchars($search); ?></b>"
        </p>

    </div>

    <!-- EMPTY SEARCH -->
    <?php if (empty($search)) { ?>
        <div class="alert alert-warning text-center rounded-4 shadow-sm">
            <i class="bi bi-exclamation-triangle me-2"></i>
            Please enter something to search.
        </div>
    <?php } ?>

    <!-- STUDENTS -->
    <div class="card shadow border-0 rounded-4 p-3 mb-4">

        <h5 class="fw-semibold mb-3">
            <i class="bi bi-mortarboard me-2"></i> Students
        </h5>

        <div class="row g-3">

            <?php if ($r1 && mysqli_num_rows($r1) > 0) { ?>

                <?php while ($row = mysqli_fetch_assoc($r1)) { ?>

                    <div class="col-md-6 col-lg-4">

                        <div class="card shadow-sm border-0 rounded-4 h-100">

                            <div class="card-body d-flex align-items-center gap-3">

                                <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png"
                                     width="50"
                                     class="rounded-circle">

                                <div>

                                    <a class="fw-semibold text-dark text-decoration-none"
                                       href="SearchProfile.php?id=<?php echo $row['enrollment']; ?>&type=student">

                                        <?php echo htmlspecialchars($row['name']); ?>

                                    </a>

                                    <div class="text-muted small">
                                        <?php echo htmlspecialchars($row['email']); ?>
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                <?php } ?>

            <?php } else { ?>

                <div class="text-muted text-center py-3">
                    No students found
                </div>

            <?php } ?>

        </div>

    </div>

    <!-- FACULTY -->
    <div class="card shadow border-0 rounded-4 p-3">

        <h5 class="fw-semibold mb-3">
            <i class="bi bi-person-badge me-2"></i> Faculty
        </h5>

        <div class="row g-3">

            <?php if ($r2 && mysqli_num_rows($r2) > 0) { ?>

                <?php while ($row = mysqli_fetch_assoc($r2)) { ?>

                    <div class="col-md-6 col-lg-4">

                        <div class="card shadow-sm border-0 rounded-4 h-100">

                            <div class="card-body d-flex align-items-center gap-3">

                                <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png"
                                     width="50"
                                     class="rounded-circle">

                                <div>

                                    <a class="fw-semibold text-dark text-decoration-none"
                                       href="SearchProfile.php?id=<?php echo $row['email']; ?>&type=faculty">

                                        <?php echo htmlspecialchars($row['name']); ?>

                                    </a>

                                    <div class="text-muted small">
                                        <?php echo htmlspecialchars($row['email']); ?>
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                <?php } ?>

            <?php } else { ?>

                <div class="text-muted text-center py-3">
                    No faculty found
                </div>

            <?php } ?>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>