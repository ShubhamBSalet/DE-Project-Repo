<?php
session_start();
include("../../_DBConnect.php");

// 🔐 Only admin
if (!isset($_SESSION['AdminLoggedin']) || $_SESSION['AdminLoggedin'] != true) {
    header("Location: ../_NotLoggedIn.php");
    exit();
}

if (isset($_GET['aid']) && isset($_GET['qid'])) {

    $aid = intval($_GET['aid']);
    $qid = intval($_GET['qid']);

    // 🔥 Delete rating first (if exists)
    mysqli_query($conn, "DELETE FROM answer_rating WHERE answer_id = $aid");

    // 🔥 Delete answer
    mysqli_query($conn, "DELETE FROM AnswerToQuestion WHERE answer_id = $aid");
}

// 🔁 Redirect back to same question
header("Location: View_question.php?qid=$qid");
exit();
?>