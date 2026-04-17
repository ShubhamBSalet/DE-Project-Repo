<?php
include("../../../_DBConnect.php");

if (isset($_POST['upload'])) {

    $year = $_POST['year'];
    $semester = $_POST['semester'];
    $subject = $_POST['subject'];

    $file = $_FILES['file']['tmp_name'];

    if (($handle = fopen($file, "r")) !== FALSE) {

        fgetcsv($handle); // skip header

        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

            $enrollment = $data[0];
            $name = $data[1];
            $total = $data[2];
            $attended = $data[3];

            // prevent division error
            $percentage = ($total > 0) ? ($attended / $total) * 100 : 0;

            $query = "INSERT INTO attendance 
            (enrollment, student_name, subject, year, semester, total_lectures, attended_lectures, attendance_percentage)
            VALUES 
            ('$enrollment','$name','$subject','$year','$semester','$total','$attended','$percentage')

            ON DUPLICATE KEY UPDATE
            student_name='$name',
            total_lectures='$total',
            attended_lectures='$attended',
            attendance_percentage='$percentage'";

            mysqli_query($conn, $query);
        }

        fclose($handle);

        header("Location: /DE-Project/Faculty/FacultyActivity/FacultyAttendance/index.php");
        exit();
    }
}
?>