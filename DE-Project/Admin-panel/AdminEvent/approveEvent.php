<?php
session_start();
include("../../_DBConnect.php");

if (!isset($_SESSION['AdminLoggedin'])) {
    header("Location: ../_NotLoggedIn.php");
    exit();
}

$id = intval($_GET['id']);

mysqli_query($conn, "UPDATE eventpost SET status='approved' WHERE event_id='$id'");

header("Location: PostEvent.php");
?>