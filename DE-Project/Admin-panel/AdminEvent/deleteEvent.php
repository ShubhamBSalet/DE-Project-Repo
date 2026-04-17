<?php
include("../../_DBConnect.php");

session_start();

    // check id contains data or not from postevent
    if (!isset($_GET['id'])) {
        header("location:PostEvent.php");
        exit();
    }

    $id = $_GET['id'];

    // delete the record from database according to id from the postevent
    $sql = "DELETE FROM eventpost WHERE event_id='$id'";
    mysqli_query($conn, $sql);

    header("location:PostEvent.php");
    exit();
?>