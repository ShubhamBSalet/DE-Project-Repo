<?php
session_start();
include("../../../_DBConnect.php");

// ✅ Check student login
if (!isset($_SESSION['studentLoggedin'])) {
    header("Location: /DE-Project/_NotLoggedIn.php");
    exit();
}

// ✅ Get student enrollment from session
$enrollment = $_SESSION['enrollment'];

/* ================= FETCH DATA ================= */
$query = "SELECT * FROM attendance WHERE enrollment='$enrollment'";
$result = mysqli_query($conn, $query);

$labels = [];
$data = [];
$colors = [];
$rows = [];

while ($row = mysqli_fetch_assoc($result)) {

    $rows[] = $row;

    $labels[] = $row['subject']; // subject wise
    $data[] = $row['attendance_percentage'];

    $colors[] = ($row['attendance_percentage'] < 75) ? "red" : "green";
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>My Attendance</title>

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

        <h3 class="fw-bold mb-1">
            <i class="bi bi-bar-chart-line me-2"></i> My Attendance
        </h3>

        <p class="text-muted mb-0">
            Track your subject-wise attendance performance
        </p>

    </div>

    <?php if (!empty($rows)) { ?>

        <!-- ================= SUMMARY CARDS ================= -->
        <div class="row g-3 mb-4">

            <?php
            $avg = array_sum($data) / count($data);
            ?>

            <div class="col-md-4">
                <div class="card shadow-sm border-0 rounded-4 p-3">
                    <small class="text-muted">Subjects</small>
                    <h4 class="fw-bold"><?= count($rows) ?></h4>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm border-0 rounded-4 p-3">
                    <small class="text-muted">Average Attendance</small>
                    <h4 class="fw-bold <?= ($avg < 75) ? 'text-danger' : 'text-success' ?>">
                        <?= round($avg, 2) ?>%
                    </h4>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm border-0 rounded-4 p-3">
                    <small class="text-muted">Status</small>
                    <h4 class="fw-bold <?= ($avg < 75) ? 'text-danger' : 'text-success' ?>">
                        <?= ($avg < 75) ? 'Low Attendance' : 'Good Standing' ?>
                    </h4>
                </div>
            </div>

        </div>


        <!-- ================= TABLE ================= -->
        <div class="card shadow border-0 rounded-4 mb-4">

            <div class="card-header bg-success text-white rounded-top-4">
                <i class="bi bi-table me-1"></i> Attendance Details
            </div>

            <div class="card-body table-responsive">

                <table class="table align-middle text-center">

                    <thead class="table-light">
                        <tr>
                            <th>Subject</th>
                            <th>Total</th>
                            <th>Attended</th>
                            <th>Percentage</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($rows as $r) { ?>
                            <tr>

                                <td class="fw-semibold"><?= $r['subject'] ?></td>
                                <td><?= $r['total_lectures'] ?></td>
                                <td><?= $r['attended_lectures'] ?></td>

                                <td>
                                    <span class="fw-bold <?= ($r['attendance_percentage'] < 75) ? 'text-danger' : 'text-success' ?>">
                                        <?= round($r['attendance_percentage'], 2) ?>%
                                    </span>
                                </td>

                                <td>
                                    <span class="badge <?= ($r['attendance_percentage'] < 75) ? 'bg-danger' : 'bg-success' ?>">
                                        <?= ($r['attendance_percentage'] < 75) ? 'Low' : 'Good' ?>
                                    </span>
                                </td>

                            </tr>
                        <?php } ?>
                    </tbody>

                </table>

            </div>

        </div>


        <!-- ================= CHART ================= -->
        <div class="card shadow border-0 rounded-4">

            <div class="card-header bg-info text-white rounded-top-4">
                <i class="bi bi-graph-up me-1"></i> Attendance Chart
            </div>

            <div class="card-body d-flex justify-content-center">

                <div style="width: 100%; max-width: 500px; height: 400px;">
                    <canvas id="attendanceChart"></canvas>
                </div>

            </div>

        </div>

    <?php } else { ?>

        <div class="alert alert-warning text-center shadow-sm rounded-4">
            <i class="bi bi-exclamation-triangle me-2"></i>
            No attendance found!
        </div>

    <?php } ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
new Chart(document.getElementById('attendanceChart'), {
    type: 'bar',
    data: {
        labels: <?= json_encode($labels) ?>,
        datasets: [{
            label: 'Attendance %',
            data: <?= json_encode($data) ?>,
            backgroundColor: <?= json_encode($colors) ?>
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});
</script>

</body>
</html>