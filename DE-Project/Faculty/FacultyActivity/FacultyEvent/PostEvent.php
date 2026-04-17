<?php
include("../../../_DBConnect.php");
session_start();

//************if faculty not logged in************
if (!isset($_SESSION['facultyLoggedin']) || $_SESSION['facultyLoggedin'] != true) {
    header("Location: /DE-Project/_NotLoggedIn.php");
    exit();
}

//************if student logged in************
if (isset($_SESSION['studentLoggedin']) && $_SESSION['studentLoggedin'] == true) {
    header("Location: ./Student/StudentCredential/HomePage.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events</title>

    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <?php include("../../loader.php"); ?>
    <?php include("../../_Navbar.php"); ?>
    <div class="container py-5">

        <!-- Header -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
            <div>
                <h2 class="fw-bold mb-0">Events</h2>
                <p class="text-muted mb-0">Manage and post campus events</p>
            </div>

        </div>

        <hr>

        <div class="row g-4">

            <?php
            $sql = "SELECT * FROM eventpost ORDER BY event_datetime DESC";
            $result = mysqli_query($conn, $sql);

            while ($row = mysqli_fetch_assoc($result)) {

                // Format date
                $formattedDate = date("d M Y, h:i A", strtotime($row['event_datetime']));

                // Short description
                $shortDetails = strlen($row['event_details']) > 100
                    ? substr($row['event_details'], 0, 100) . "..."
                    : $row['event_details'];
            ?>

                <div class="col-12 col-md-6 col-lg-4">

                    <div class="card shadow-sm border-0 rounded-4 h-100">

                        <div class="card-body d-flex flex-column">

                            <!-- Title -->
                            <h5 class="fw-bold mb-2">
                                <?php echo $row['event_name']; ?>
                            </h5>

                            <!-- Date Badge -->
                            <span class="badge bg-secondary mb-3">
                                <?php echo $formattedDate; ?>
                            </span>

                            <!-- Info -->
                            <p class="mb-2">
                                <b class="text-muted">Place:</b> <?php echo $row['event_place']; ?>
                            </p>

                            <p class="mb-2">
                                <b class="text-muted">Organized by:</b> <?php echo $row['event_organizer']; ?>
                            </p>

                            <!-- Description -->
                            <p class="mb-3">
                                <b class="text-muted">Description:</b> <?php echo $shortDetails; ?>
                            </p>

                            <!-- Buttons -->
                            <div class="mt-auto">

                                <!-- Register -->
                                <a href="<?php echo $row['event_form_link']; ?>"
                                    target="_blank"
                                    class="btn btn-primary w-100 rounded-pill mb-2">Register
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