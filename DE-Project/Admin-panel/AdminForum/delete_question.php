<?php
session_start();
include("../../_DBConnect.php");

// 🔐 Only admin can delete
if (!isset($_SESSION['AdminLoggedin']) || $_SESSION['AdminLoggedin'] != true) {
    header("Location: ../_NotLoggedIn.php");
    exit();
}

// ✅ Check question id
if (isset($_GET['qid'])) {

    $qid = intval($_GET['qid']);

    // 🔥 First delete answers (to avoid foreign key error)
    $deleteAnswers = "DELETE FROM AnswerToQuestion WHERE question_id = $qid";
    mysqli_query($conn, $deleteAnswers);

    // 🔥 Then delete question
    $deleteQuestion = "DELETE FROM questionask WHERE question_id = $qid";
    mysqli_query($conn, $deleteQuestion);
}

// 🔁 Redirect back
header("Location: /DE-Project/Admin-panel/AdminForum/AdminForum.php");
exit();
?>