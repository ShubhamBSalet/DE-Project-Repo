<?php
session_start();
include("../../_DBConnect.php");


// 🔐 Check admin login
if (!isset($_SESSION['AdminLoggedin']) || $_SESSION['AdminLoggedin'] != true) {
    header("Location: ../_NotLoggedIn.php");
    exit();
}

/* DEBUG */
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "ID not received!";
    exit();
}

$id = $_GET['id'];

/* FETCH DATA */
$sql = "SELECT * FROM eventpost WHERE event_id='$id'";
$result = mysqli_query($conn, $sql);

if (!$result || mysqli_num_rows($result) == 0) {
    echo "Event not found!";
    exit();
}

$row = mysqli_fetch_assoc($result);

/* UPDATE */
if (isset($_POST['update'])) {

    $name = $_POST['name'];
    $place = $_POST['place'];
    $details = $_POST['details'];
    $datetime = $_POST['datetime'];
    $organizer = $_POST['organizer'];
    $link = $_POST['link'];

    $sql = "UPDATE eventpost SET 
        event_name='$name',
        event_place='$place',
        event_details='$details',
        event_datetime='$datetime',
        event_organizer='$organizer',
        event_form_link='$link'
        WHERE event_id='$id'";

    mysqli_query($conn, $sql);

    header("Location: PostEvent.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event</title>

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
                    <h4 class="mb-0 fw-semibold">Edit Event</h4>
                </div>

                <!-- Body -->
                <div class="card-body p-4">

                    <form method="post">

                        <!-- Event Name -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Event Name</label>
                            <input type="text" class="form-control rounded-3"
                                name="name"
                                value="<?php echo $row['event_name']; ?>"
                                required>
                        </div>

                        <!-- Place -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Place</label>
                            <input type="text" class="form-control rounded-3"
                                name="place"
                                value="<?php echo $row['event_place']; ?>"
                                required>
                        </div>

                        <!-- Details -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Details</label>
                            <textarea class="form-control rounded-3"
                                name="details"
                                rows="3"
                                required><?php echo $row['event_details']; ?></textarea>
                        </div>

                        <!-- Date Time -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Date & Time</label>
                            <input type="datetime-local"
                                class="form-control rounded-3"
                                name="datetime"
                                value="<?php echo date('Y-m-d\TH:i', strtotime($row['event_datetime'])); ?>"
                                required>
                        </div>

                        <!-- Organizer -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Organizer</label>
                            <input type="text"
                                class="form-control rounded-3"
                                name="organizer"
                                value="<?php echo $row['event_organizer']; ?>"
                                required>
                        </div>

                        <!-- Form Link -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Registration Link</label>
                            <input type="url"
                                class="form-control rounded-3"
                                name="link"
                                value="<?php echo $row['event_form_link']; ?>"
                                placeholder="https://example.com"
                                required>
                        </div>

                        <!-- Buttons -->
                        <div class="d-grid gap-2">

                            <button name="update"
                                class="btn btn-dark rounded-pill py-2">
                                Update Event
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