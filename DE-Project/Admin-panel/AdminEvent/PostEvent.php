<?php
include("../../_DBConnect.php");
session_start();

if (!isset($_SESSION['AdminLoggedin'])) {
    header("Location: ../_NotLoggedIn.php");
    exit();
}

$status = "";

if (isset($_GET['status']) && $_GET['status'] != "") {
    $status = $_GET['status'];
    $sql = "SELECT * FROM eventpost WHERE status='$status' ORDER BY event_datetime DESC";
} else {
    $sql = "SELECT * FROM eventpost ORDER BY status='pending' DESC, event_datetime DESC";
}

$result = mysqli_query($conn, $sql);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <?php include("../_Navbar.php"); ?>

    <div class="container py-5">

        <!-- HEADER -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
            <div>
                <h2 class="fw-bold mb-0">Events</h2>
                <p class="text-muted mb-0">Manage and post campus events</p>
            </div>

            <div class="d-flex gap-2">

                <!-- FILTER DROPDOWN -->
                <form method="GET">
                    <select name="status"
                        class="form-select rounded-pill px-3"
                        onchange="this.form.submit()">

                        <option value="">All</option>

                        <option value="approved"
                            <?php if ($status == 'approved') echo 'selected'; ?>>
                            Approved
                        </option>

                        <option value="pending"
                            <?php if ($status == 'pending') echo 'selected'; ?>>
                            Pending
                        </option>

                        <option value="rejected"
                            <?php if ($status == 'rejected') echo 'selected'; ?>>
                            Rejected
                        </option>

                    </select>
                </form>

                <!-- ADD EVENT -->
                <a href="./addEvent.php" class="btn btn-dark rounded-pill px-4">
                    + Post Event
                </a>

            </div>
        </div>

        <hr>

        <div class="row g-4">

            <?php if (mysqli_num_rows($result) == 0) { ?>
                <div class="alert alert-info">
                    No events found.
                </div>
            <?php } ?>

            <?php while ($row = mysqli_fetch_assoc($result)) {

                $formattedDate = date("d M Y, h:i A", strtotime($row['event_datetime']));

                $shortDetails = strlen($row['event_details']) > 100
                    ? substr($row['event_details'], 0, 100) . "..."
                    : $row['event_details'];
            ?>

                <div class="col-12 col-md-6 col-lg-4">

                    <div class="card shadow-sm border-0 rounded-4 h-100">

                        <div class="card-body d-flex flex-column">

                            <!-- TITLE -->
                            <h5 class="fw-bold mb-2">
                                <?php echo $row['event_name']; ?>
                            </h5>

                            <!-- STATUS BADGE -->
                            <span class="badge mb-2 
                    <?php
                    if ($row['status'] == 'approved') echo 'bg-success';
                    elseif ($row['status'] == 'pending') echo 'bg-warning text-dark';
                    else echo 'bg-danger';
                    ?>">
                                <?php echo ucfirst($row['status']); ?>
                            </span>

                            <!-- DATE -->
                            <span class="badge bg-secondary mb-3">
                                <?php echo $formattedDate; ?>
                            </span>

                            <!-- INFO -->
                            <p class="mb-2">
                                <b class="text-muted">Place:</b> <?php echo $row['event_place']; ?>
                            </p>

                            <p class="mb-2">
                                <b class="text-muted">Organized by:</b> <?php echo $row['event_organizer']; ?>
                            </p>

                            <!-- DESCRIPTION -->
                            <p class="mb-3">
                                <b class="text-muted">Description:</b> <?php echo $shortDetails; ?>
                            </p>

                            <!-- BUTTONS -->
                            <div class="mt-auto">

                                <a href="<?php echo $row['event_form_link']; ?>"
                                    target="_blank"
                                    class="btn btn-success w-100 rounded-pill mb-2">
                                    View / Register
                                </a>

                                <div class="d-flex gap-2">

                                    <a href="editEvent.php?id=<?php echo $row['event_id']; ?>"
                                        class="btn btn-outline-primary w-50 rounded-pill">
                                        Edit
                                    </a>

                                    <a href="deleteEvent.php?id=<?php echo $row['event_id']; ?>"
                                        class="btn btn-outline-danger w-50 rounded-pill"
                                        onclick="return confirm('Delete this event?');">
                                        Delete
                                    </a>

                                </div>

                            </div>

                        </div>

                        <p><b>Posted By:</b> <?php echo $row['posted_by']; ?></p>
                        <p><b>Role:</b> <?php echo ucfirst($row['role']); ?></p>

                        <!-- APPROVE / REJECT ONLY FOR PENDING -->
                        <?php if ($row['status'] == 'pending') { ?>

                            <div class="d-flex gap-2 p-3">

                                <a href="approveEvent.php?id=<?php echo $row['event_id']; ?>"
                                    class="btn btn-success w-50 rounded-pill">
                                    Approve
                                </a>

                                <a href="rejectEvent.php?id=<?php echo $row['event_id']; ?>"
                                    class="btn btn-warning w-50 rounded-pill">
                                    Reject
                                </a>

                            </div>

                        <?php } ?>

                    </div>

                </div>

            <?php } ?>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>