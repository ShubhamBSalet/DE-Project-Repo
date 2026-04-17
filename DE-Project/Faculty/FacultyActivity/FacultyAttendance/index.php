<?php
session_start();
include("../../../_DBConnect.php");

if (!isset($_SESSION['facultyLoggedin'])) {
    header("Location: /DE-Project/_NotLoggedIn.php");
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Attendance Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <?php include("../../loader.php"); ?>
    <?php include("../../_Navbar.php"); ?>

    <div class="container mt-4">

        <!-- Upload Card -->
        <div class="card shadow mb-4">
            <div class="card-header bg-primary text-white">
                Upload Attendance
            </div>

            <div class="card-body">

                <form action="upload_attendance.php" method="POST" enctype="multipart/form-data" class="row g-3">

                    <div class="col-md-3">
                        <select name="year" class="form-select" required>
                            <option value="">Year</option>
                            <option value="1">1st</option>
                            <option value="2">2nd</option>
                            <option value="3">3rd</option>
                            <option value="4">4th</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <select name="semester" class="form-select" required>
                            <option value="">Semester</option>
                            <option value="1">Sem 1</option>
                            <option value="2">Sem 2</option>
                            <option value="3">Sem 3</option>
                            <option value="4">Sem 4</option>
                            <option value="5">Sem 5</option>
                            <option value="6">Sem 6</option>
                            <option value="7">Sem 7</option>
                            <option value="8">Sem 8</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <input type="text" name="subject" class="form-control" placeholder="Subject" required>
                    </div>

                    <div class="col-md-3">
                        <input type="file" name="file" class="form-control" accept=".csv" required>
                    </div>

                    <div class="col-12">
                        <button class="btn btn-success w-100" name="upload">Upload CSV</button>
                    </div>

                </form>
                <div class="mt-3 text-muted">

                    <strong>Rules:</strong>

                    <ul class="mb-0">

                        <li>The file type should be CSV (.csv)</li>

                        <li>The file must contain the following columns in the first row:<br>enrollment, student_name, total_lectures, attended_lectures</li>

                        <li>Do not include subject, year, semester in CSV (they are selected above)</li>

                        <li>Enrollment number must be valid and unique per student</li>

                        <li>Total lectures must be greater than 0</li>

                        <li>Attended lectures must be less than or equal to total lectures</li>

                        <li>Duplicate records will be automatically updated (not inserted again)</li>

                    </ul>

                </div>
            </div>
        </div>
        <hr>
        <br>
        <!-- Filter Card -->
        <div class="card shadow mb-4">
            <div class="card-header bg-dark text-white">
                View Attendance
            </div>

            <div class="card-body">

                <form method="GET" class="row g-3">

                    <div class="col-md-3">
                        <select name="year" class="form-select">
                            <option value="">Select Year</option>

                            <?php
                            $yearResult = mysqli_query($conn, "SELECT DISTINCT year FROM attendance ORDER BY year");
                            while ($y = mysqli_fetch_assoc($yearResult)) {
                                echo "<option value='{$y['year']}'>{$y['year']}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <select name="semester" class="form-select">
                            <option value="">Select Semester</option>

                            <?php
                            $semResult = mysqli_query($conn, "SELECT DISTINCT semester FROM attendance ORDER BY semester");
                            while ($s = mysqli_fetch_assoc($semResult)) {
                                echo "<option value='{$s['semester']}'>Sem {$s['semester']}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <select name="subject" class="form-select">
                            <option value="">Select Subject</option>

                            <?php
                            $subResult = mysqli_query($conn, "SELECT DISTINCT subject FROM attendance ORDER BY subject");
                            while ($sub = mysqli_fetch_assoc($subResult)) {
                                echo "<option value='{$sub['subject']}'>{$sub['subject']}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <button class="btn btn-primary w-100">Show</button>
                    </div>

                </form>

            </div>
        </div>
        <hr>
        <br>
        <!-- 🔥 INCLUDE VIEW -->
        <?php include("view_attendance.php"); ?>

    </div>

</body>

</html>