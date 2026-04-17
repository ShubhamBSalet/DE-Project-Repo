<?php
session_start();
include("../../_DBConnect.php");

// 🔐 Admin check
if (!isset($_SESSION['AdminLoggedin']) || $_SESSION['AdminLoggedin'] != true) {
    header("Location: ../_NotLoggedIn.php");
    exit();
}

if (isset($_POST['upload'])) {

    // ✅ Check file extension
    $fileName = $_FILES['excel']['name'];
    $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if ($ext != 'csv') {
        echo "<script>
                alert('❌ Only CSV file allowed');
                window.location='Add_student_excel.php';
            </script>";
    }

    $file = fopen($_FILES['excel']['tmp_name'], "r");

    if (!$file) {
        die("❌ Unable to open file");
    }

    $rowNumber = 0;
    $inserted = 0;
    $skipped = 0;

    while (($row = fgetcsv($file)) !== FALSE) {

        // 🔹 Skip header row
        if ($rowNumber == 0) {
            $rowNumber++;
            continue;
        }

        // 🔹 Check if row has all columns
        if (count($row) < 5) {
            $skipped++;
            $rowNumber++;
            continue;
        }

        // 🔹 Get data safely
        $enrollment = mysqli_real_escape_string($conn, trim($row[0]));
        $name       = mysqli_real_escape_string($conn, trim($row[1]));
        $email      = mysqli_real_escape_string($conn, trim($row[2]));
        $branch     = mysqli_real_escape_string($conn, trim($row[3]));
        $mobile     = mysqli_real_escape_string($conn, trim($row[4]));

        // 🔹 Validation
        if (!$enrollment || !$name || !$email || !$branch || !$mobile) {
            $skipped++;
            $rowNumber++;
            continue;
        }

        if (!preg_match('/^[0-9]{12}$/', $enrollment)) {
            $skipped++;
            $rowNumber++;
            continue;
        }

        if (!preg_match('/^[0-9]{10}$/', $mobile)) {
            $skipped++;
            $rowNumber++;
            continue;
        }

        // 🔹 Valid branches
        $validBranches = ['CE', 'IT', 'EC', 'ICT', 'ME', 'EE'];
        if (!in_array($branch, $validBranches)) {
            $skipped++;
            $rowNumber++;
            continue;
        }

        // 🔹 Duplicate check
        $check = "SELECT * FROM studentdata 
                  WHERE enrollment='$enrollment' 
                  OR email='$email' 
                  OR mobile='$mobile'";

        $res = mysqli_query($conn, $check);

        if (mysqli_num_rows($res) == 0) {

            $insert = "INSERT INTO studentdata 
            (enrollment, name, email, branch, mobile) 
            VALUES 
            ('$enrollment', '$name', '$email', '$branch', '$mobile')";

            if (mysqli_query($conn, $insert)) {
                $inserted++;
            } else {
                echo "SQL Error: " . mysqli_error($conn) . "<br>";
            }
        } else {
            $skipped++;
        }

        $rowNumber++;
    }

    fclose($file);

    echo "<script>
        alert('✅ Inserted: $inserted | ❌ Skipped: $skipped');
        window.location='Show_student_data.php';
    </script>";
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Upload CSV</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <?php include("../_Navbar.php"); ?>

    <div class="container mt-5">

        <div class="card shadow p-4">

            <h4 class="text-center mb-3">Upload Student CSV File</h4>

            <form method="POST" enctype="multipart/form-data">

                <div class="mb-3">
                    <input type="file" name="excel" class="form-control" required>
                </div>

                <button type="submit" name="upload" class="btn btn-dark w-100">
                    Upload & Import
                </button>

                <a href="Add_student_excel.php" class="btn btn-light w-100 mt-3">Back</a>

            </form>

            <div class="mt-3 text-muted">
                <strong>Rules:</strong><br>
                <ul>
                    <li>The file type should be CSV (ex.csv)</li>
                    <li>the file should contain following columns as a first line [Enrollment, Name, Email, Branch, Mobile]</li>
                    <li>The Enrollment length should be 12 digits & must be unique from exitsiting Enrollments in the records.</li>
                    <li>The email id should be unique from exitsiting email id's in the records.</li>
                    <li>the branch should be like this formate -> ['CE', 'IT', 'EC', 'ICT', 'ME', 'EE']</li>
                    <li>The mobile number should contain 10 digit of number & must be unique from exitsiting mobile number in the records.</li>
                </ul>
            </div>

        </div>

    </div>

</body>

</html>