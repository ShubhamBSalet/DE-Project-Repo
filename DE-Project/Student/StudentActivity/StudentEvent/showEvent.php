<?php
session_start();
include("../../../_DBConnect.php");

//****************if student not logged in****************
if (!isset($_SESSION['studentLoggedin'])) {
    header("Location: /DE-Project/_NotLoggedIn.php");
    exit();
}

//****************if faculty logged in****************
if (isset($_SESSION['facultyLoggedin']) && $_SESSION['facultyLoggedin'] == true) {
    header("Location: ./Faculty/FacultyCredential/HomePage.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-body-tertiary">

<?php include("../../loader.php"); ?>
<?php include("../../_Navbar.php"); ?>

<div class="container py-5">

    <!-- ================= HEADER ================= -->
    <div class="card shadow border-0 rounded-4 p-4 mb-4">

        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">

            <div>
                <h3 class="fw-bold mb-1">
                    <i class="bi bi-calendar-event me-2"></i> Events
                </h3>
                <p class="text-muted mb-0">
                    Discover and register for campus activities
                </p>
            </div>

            <a href="StudentAddEvent.php"
               class="btn btn-dark rounded-pill px-4">
                <i class="bi bi-plus-circle me-1"></i> Add Event
            </a>

        </div>

    </div>


    <!-- ================= EVENTS ================= -->
    <div class="row g-4">

        <?php
        $sql = "SELECT * FROM eventpost 
                WHERE status='approved' 
                ORDER BY event_datetime DESC";
        $result = mysqli_query($conn, $sql);

        while ($row = mysqli_fetch_assoc($result)) {

            $formattedDate = date("d M Y, h:i A", strtotime($row['event_datetime']));

            $shortDetails = strlen($row['event_details']) > 100
                ? substr($row['event_details'], 0, 100) . "..."
                : $row['event_details'];
        ?>

            <div class="col-md-6 col-lg-4">

                <div class="card shadow-sm border-0 rounded-4 h-100">

                    <div class="card-body d-flex flex-column">

                        <!-- Title -->
                        <h5 class="fw-bold mb-2">
                            <?php echo $row['event_name']; ?>
                        </h5>

                        <!-- Date -->
                        <span class="badge bg-primary mb-3 align-self-start">
                            <i class="bi bi-clock me-1"></i>
                            <?php echo $formattedDate; ?>
                        </span>

                        <!-- Info -->
                        <p class="mb-1 small">
                            <i class="bi bi-geo-alt me-1 text-muted"></i>
                            <?php echo $row['event_place']; ?>
                        </p>

                        <p class="mb-2 small">
                            <i class="bi bi-person me-1 text-muted"></i>
                            <?php echo $row['event_organizer']; ?>
                        </p>

                        <!-- Description -->
                        <p class="text-muted small mb-3">
                            <?php echo $shortDetails; ?>
                        </p>

                        <!-- Button -->
                        <div class="mt-auto">
                            <a href="<?php echo $row['event_form_link']; ?>"
                               target="_blank"
                               class="btn btn-primary w-100 rounded-pill">
                                <i class="bi bi-box-arrow-up-right me-1"></i>
                                Register Now
                            </a>
                        </div>

                    </div>

                </div>

            </div>

        <?php } ?>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>