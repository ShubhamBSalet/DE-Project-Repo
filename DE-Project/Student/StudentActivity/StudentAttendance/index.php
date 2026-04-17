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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <?php include("../../loader.php"); ?>

    <?php include("../../_Navbar.php"); ?>

    <div class="container mt-4">

        <h4 class="mb-4">My Attendance</h4>

        <?php if (!empty($rows)) { ?>

            <!-- TABLE -->
            <div class="card shadow mb-4">
                <div class="card-header bg-success text-white">
                    Attendance Details
                </div>

                <div class="card-body table-responsive">

                    <table class="table table-bordered text-center">
                        <tr>
                            <th>Subject</th>
                            <th>Total</th>
                            <th>Attended</th>
                            <th>%</th>
                        </tr>

                        <?php foreach ($rows as $r) { ?>
                            <tr style="color: <?= ($r['attendance_percentage'] < 75) ? 'red' : 'green' ?>">
                                <td><?= $r['subject'] ?></td>
                                <td><?= $r['total_lectures'] ?></td>
                                <td><?= $r['attended_lectures'] ?></td>
                                <td><?= round($r['attendance_percentage'], 2) ?>%</td>
                            </tr>
                        <?php } ?>

                    </table>

                </div>
            </div>

            <!-- CHART -->
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    Attendance Chart
                </div>

                <div class="card-body d-flex justify-content-center">
                    <div style="width: 400px; height: 400px;">
                        <canvas id="attendanceChart"></canvas>
                    </div>
                </div>
            </div>

        <?php } else { ?>

            <div class="alert alert-warning text-center">
                No attendance found!
            </div>

        <?php } ?>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        new Chart(document.getElementById('attendanceChart'), {
            type: 'bar', // ✅ better for students
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