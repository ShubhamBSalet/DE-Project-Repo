<?php
session_start();
include("../../../_DBConnect.php");

if (isset($_POST['ask'])) {

    $question = $_POST['question'];

    // detect user
    if (isset($_SESSION['enrollment'])) {

        $user = $_SESSION['enrollment'];

    } elseif (isset($_SESSION['email'])) {

        $user = $_SESSION['email'];

    } else {

        die("User not logged in");
    }

    $query = "INSERT INTO questionask(user_enrollment,question)
              VALUES('$user','$question')";

    mysqli_query($conn, $query);

    header("Location: FacultyForum.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Ask Question</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-body-tertiary">

<?php include("../../_Navbar.php"); ?>

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-lg-7 col-md-9">

            <div class="card shadow border-0 rounded-4">

                <!-- HEADER -->
                <div class="card-header bg-success text-white text-center rounded-top-4 py-3">
                    <h4 class="mb-0">
                        <i class="bi bi-question-circle me-2"></i> Ask a Question
                    </h4>
                </div>

                <!-- BODY -->
                <div class="card-body p-4">

                    <form method="post">

                        <!-- INFO BOX -->
                        <div class="alert alert-light border mb-4">
                            💡 Ask clearly so students and faculty can help you better.
                        </div>

                        <!-- TEXTAREA -->
                        <div class="form-floating mb-3">

                            <textarea name="question"
                                      class="form-control"
                                      id="questionBox"
                                      placeholder="Type your question"
                                      style="height: 150px"
                                      required></textarea>

                            <label for="questionBox">
                                <i class="bi bi-pencil-square me-1"></i> Write your question
                            </label>

                        </div>

                        <!-- HELP TEXT -->
                        <div class="text-muted small mb-3">
                            Be specific. Include topic, context, and details.
                        </div>

                        <!-- BUTTONS -->
                        <div class="d-flex gap-2">

                            <button name="ask"
                                    class="btn btn-success w-100 py-2 rounded-pill">
                                <i class="bi bi-send me-1"></i> Post Question
                            </button>

                            <a href="./FacultyForum.php"
                               class="btn btn-outline-secondary w-100 py-2 rounded-pill">
                                <i class="bi bi-arrow-left me-1"></i> Back
                            </a>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>