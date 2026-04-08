<?php
session_start();
include("../../../_DBConnect.php");

// ================= POST QUESTION =================
if (isset($_POST['ask'])) {

    $question = $_POST['question'];
    $enrollment = $_SESSION['enrollment'];

    mysqli_query(
        $conn,
        "INSERT INTO questionask(user_enrollment,question)
         VALUES ('$enrollment','$question')"
    );

    header("Location: StudentForum.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Ask Question</title>

    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <?php include("../../_Navbar.php"); ?>

    <div class="container py-5">

        <div class="row justify-content-center">

            <div class="col-12 col-md-10 col-lg-8 col-xl-6">

                <div class="card shadow-sm border-0 rounded-4">

                    <!-- HEADER -->
                    <div class="card-header bg-primary text-white text-center rounded-top-4">
                        <h4 class="mb-0 fw-semibold">Ask a Question</h4>
                    </div>

                    <!-- BODY -->
                    <div class="card-body p-4">

                        <form method="post">

                            <!-- TEXTAREA -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Your Question
                                </label>

                                <textarea name="question"
                                    class="form-control form-control-lg"
                                    rows="5"
                                    placeholder="Type your question here..."
                                    required></textarea>
                            </div>

                            <!-- BUTTONS -->
                            <div class="d-flex flex-wrap gap-2">

                                <button name="ask"
                                    class="btn btn-primary rounded-pill px-4">
                                    Post Question
                                </button>

                                <a href="./StudentForum.php"
                                    class="btn btn-outline-dark rounded-pill px-4">
                                    Back
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