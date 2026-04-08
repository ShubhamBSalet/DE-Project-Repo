<?php
session_start();

if (!isset($_SESSION['AdminLoggedin']) || $_SESSION['AdminLoggedin'] != true) {
    header("Location: ../_NotLoggedIn.php");
    exit();
}

include("../../_DBConnect.php");

if (isset($_GET['id'])) {

    $id = $_GET['id'];

    // Delete query
    $query = "DELETE FROM studentdata WHERE enrollment='$id'";
    mysqli_query($conn, $query);

    // Redirect back
    header("Location: Show_student_data.php");
    exit();
}
?>