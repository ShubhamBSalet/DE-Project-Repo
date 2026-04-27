<?php
session_start();
include("../../../_DBConnect.php");

if (!isset($_SESSION['studentLoggedin'])) {
    header("Location: /DE-Project/_NotLoggedIn.php");
    exit();
}

if (isset($_POST['add'])) {

    $name = $_POST['name'];
    $place = $_POST['place'];
    $details = $_POST['details'];
    $datetime = $_POST['datetime'];
    $organizer = $_POST['organizer'];
    $link = $_POST['link'];
    $enroll = $_SESSION['enrollment'];

    $query = "INSERT INTO eventpost 
    (event_name, event_place, event_details, event_datetime, event_organizer, event_form_link, status, posted_by, role) 
    VALUES 
    ('$name','$place','$details','$datetime','$organizer','$link','pending','$enroll','student')";

    mysqli_query($conn, $query);

    echo "<script>alert('Event sent for approval!'); window.location='showEvent.php';</script>";
}
?>

<!-- SAME FORM UI AS ADMIN -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Event</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-body-tertiary">

    <?php include("../../loader.php"); ?>
    <?php include("../../_Navbar.php"); ?>

    <div class="container py-5">

        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">

                <div class="card shadow border-0 rounded-4">

                    <!-- Header -->
                    <div class="card-header bg-primary text-white text-center rounded-top-4 py-3">
                        <h4 class="mb-0">
                            <i class="bi bi-calendar-plus me-2"></i> Add New Event
                        </h4>
                    </div>

                    <!-- Body -->
                    <div class="card-body p-4">

                        <form method="post">

                            <!-- Event Name -->
                            <div class="form-floating mb-3">
                                <input type="text" name="name" class="form-control" id="eventName" placeholder="Event Name" required>
                                <label for="eventName"><i class="bi bi-card-text me-1"></i> Event Name</label>
                            </div>

                            <!-- Place -->
                            <div class="form-floating mb-3">
                                <input type="text" name="place" class="form-control" id="place" placeholder="Place" required>
                                <label for="place"><i class="bi bi-geo-alt me-1"></i> Location</label>
                            </div>

                            <!-- Details -->
                            <div class="form-floating mb-3">
                                <textarea name="details" class="form-control" placeholder="Details" id="details" style="height: 100px" required></textarea>
                                <label for="details"><i class="bi bi-info-circle me-1"></i> Event Details</label>
                            </div>

                            <!-- Date Time -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-clock me-1"></i> Date & Time
                                </label>
                                <input type="datetime-local" name="datetime" class="form-control" required>
                            </div>

                            <!-- Organizer -->
                            <div class="form-floating mb-3">
                                <input type="text" name="organizer" class="form-control" id="organizer" placeholder="Organizer" required>
                                <label for="organizer"><i class="bi bi-person me-1"></i> Organizer</label>
                            </div>

                            <!-- Link -->
                            <div class="form-floating mb-4">
                                <input type="url" name="link" class="form-control" id="link" placeholder="Link" required>
                                <label for="link"><i class="bi bi-link-45deg me-1"></i> Registration Link</label>
                            </div>

                            <!-- Buttons -->
                            <div class="d-flex gap-2">
                                <button name="add" class="btn btn-dark w-100 py-2 rounded-pill">
                                    <i class="bi bi-send me-1"></i> Submit for Approval
                                </button>

                                <a href="./showEvent.php" class="btn btn-outline-secondary w-100 py-2 rounded-pill">
                                    <i class="bi bi-arrow-left me-1"></i> Back
                                </a>
                            </div>

                        </form>

                    </div>

                </div>

            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
<!-- Just change button text -->
<button name="add" class="btn btn-dark">Submit for Approval</button>