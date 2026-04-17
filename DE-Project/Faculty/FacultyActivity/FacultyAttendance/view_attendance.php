<?php
/* ================= FETCH DATA ================= */
$labels = [];
$data = [];
$colors = [];
$rows = [];

if (isset($_GET['year']) && $_GET['year'] != "") {

    $year = $_GET['year'];
    $sem = $_GET['semester'];
    $subject = $_GET['subject'];

    $query = "SELECT * FROM attendance 
              WHERE year='$year' AND semester='$sem' AND subject='$subject'";

    $result = mysqli_query($conn, $query);

    while ($row = mysqli_fetch_assoc($result)) {

        $rows[] = $row;

        $labels[] = $row['enrollment'];
        $data[] = $row['attendance_percentage'];

        $colors[] = ($row['attendance_percentage'] < 75) ? "red" : "green";
    }
}
?>

<?php if (!empty($rows)) { ?>

    <!-- TABLE -->
    <div class="card shadow mb-4">
        <div class="card-header bg-success text-white">
            Attendance Table
        </div>

        <div class="card-body table-responsive">

            <table class="table table-bordered text-center">
                <tr>
                    <th>Enrollment</th>
                    <th>Name</th>
                    <th>%</th>
                </tr>

                <?php foreach ($rows as $r) { ?>
                    <tr style="color: <?= ($r['attendance_percentage'] < 75) ? 'red' : 'green' ?>">
                        <td><?= $r['enrollment'] ?></td>
                        <td><?= $r['student_name'] ?></td>
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

    <!-- CHART SCRIPT -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        new Chart(document.getElementById('attendanceChart'), {
            type: 'pie',
            data: {
                labels: <?= json_encode($labels) ?>,
                datasets: [{
                    data: <?= json_encode($data) ?>,
                    backgroundColor: <?= json_encode($colors) ?>
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>

<?php } else if (isset($_GET['year'])) { ?>

    <div class="alert alert-warning text-center">
        No attendance data found!
    </div>

<?php } ?>