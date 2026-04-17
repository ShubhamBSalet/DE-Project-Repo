<?php
include("../../_DBConnect.php");
session_start();

    // 🔐 Check admin login
if (!isset($_SESSION['AdminLoggedin']) || $_SESSION['AdminLoggedin'] != true) {
    header("Location: ../_NotLoggedIn.php");
    exit();
}


    //************check form contains data************
    if (isset($_POST['add'])) {
        $name = $_POST['name'];
        $place = $_POST['place'];
        $details = $_POST['details'];
        $datetime = $_POST['datetime'];
        $organizer = $_POST['organizer'];
        $link = $_POST['link'];

        //************insert data to database************
        $query = "INSERT INTO eventpost (event_name, event_place, event_details, event_datetime, event_organizer, event_form_link)
                VALUES ('$name','$place','$details','$datetime','$organizer','$link')";

        mysqli_query($conn, $query);

        header("location:PostEvent.php");
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Event</title>

    <!-- Latest Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<?php include("../_Navbar.php"); ?>

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5">

            <div class="card shadow-lg border-0 rounded-4">

                <!-- Header -->
                <div class="card-header bg-dark text-white text-center rounded-top-4">
                    <h4 class="mb-0 fw-semibold">Add Event</h4>
                </div>

                <!-- Body -->
                <div class="card-body p-4">

                    <form method="post" action="">

                        <!-- Event Name -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Event Name</label>
                            <input type="text"
                                name="name"
                                class="form-control rounded-3"
                                placeholder="Enter event name"
                                required>
                        </div>

                        <!-- Place -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Place</label>
                            <input type="text"
                                name="place"
                                class="form-control rounded-3"
                                placeholder="Enter event location"
                                required>
                        </div>

                        <!-- Details -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Details</label>
                            <textarea name="details"
                                class="form-control rounded-3"
                                rows="3"
                                placeholder="Enter event details"
                                required></textarea>
                        </div>

                        <!-- Date Time -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Date & Time</label>
                            <input type="datetime-local"
                                name="datetime"
                                class="form-control rounded-3"
                                required>
                        </div>

                        <!-- Organizer -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Organizer</label>
                            <input type="text"
                                name="organizer"
                                class="form-control rounded-3"
                                placeholder="Organizer name"
                                required>
                        </div>

                        <!-- Form Link -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Registration Link</label>
                            <input type="url"
                                name="link"
                                class="form-control rounded-3"
                                placeholder="https://example.com"
                                required>
                        </div>

                        <!-- Buttons -->
                        <div class="d-grid gap-2">

                            <button name="add"
                                class="btn btn-dark rounded-pill py-2">
                                Add Event
                            </button>

                            <a href="./PostEvent.php"
                                class="btn btn-outline-dark rounded-pill py-2">
                                Back
                            </a>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>