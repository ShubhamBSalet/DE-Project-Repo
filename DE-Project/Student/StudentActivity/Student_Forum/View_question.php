<?php
session_start();
include("../../../_DBConnect.php");

//************check student logged not in************
if (!isset($_SESSION['studentLoggedin']) || $_SESSION['studentLoggedin'] != true) {
    header("Location: /DE-Project/_NotLoggedIn.php");
    exit();
}

//************check faculty logged in************
if (isset($_SESSION['facultyLoggedin']) && $_SESSION['facultyLoggedin'] == true) {
    header("Location: /DE-Project/Faculty/FacultyCredential/HomePage.php");
    exit();
}

/* ================= GET QID ================= */
if (!isset($_GET['qid'])) {
    header("Location: StudentForum.php");
    exit();
}

$qid = $_GET['qid'];

/* ================= POST ANSWER ================= */
if (isset($_POST['postAnswer'])) {

    $ans = mysqli_real_escape_string($conn, $_POST['answer']);
    $enroll = $_SESSION['enrollment'];

    mysqli_query(
        $conn,
        "INSERT INTO AnswerToQuestion (question_id,user_enrollment,answer)
            VALUES('$qid','$enroll','$ans')"
    );
}

/* ================= RATE ANSWER ================= */
if (isset($_POST['rateAnswer'])) {

    $answer_id = $_POST['answer_id'];
    $rating = $_POST['rating'];
    $rating = $_POST['rating'];

    if ($rating == 3) {
        $label = "Very Good";
    } elseif ($rating == 2) {
        $label = "Good";
    } else {
        $label = "Poor";
    }

    $enroll = $_SESSION['enrollment'];

    $check = mysqli_query(
        $conn,
        "SELECT * FROM answer_rating 
            WHERE answer_id='$answer_id' 
            AND user_enrollment='$enroll'"
    );

    if (mysqli_num_rows($check) > 0) {

        mysqli_query(
            $conn,
            "UPDATE answer_rating 
     SET rating='$rating', rating_label='$label' 
     WHERE answer_id='$answer_id' 
     AND user_enrollment='$enroll'"
        );
    } else {

        mysqli_query(
            $conn,
            "INSERT INTO answer_rating (answer_id,user_enrollment,rating,rating_label)
     VALUES ('$answer_id','$enroll','$rating','$label')"
        );
    }
}

/* ================= FETCH QUESTION ================= */
$q = mysqli_query(
    $conn,
    "SELECT * FROM questionask WHERE question_id='$qid'"
);
$row = mysqli_fetch_assoc($q);

/* ================= FILTER ================= */
$sort = "";

if (isset($_GET['filter'])) {
    if ($_GET['filter'] == "high") $sort = "ORDER BY avg_rating DESC";
    if ($_GET['filter'] == "low")  $sort = "ORDER BY avg_rating ASC";
    if ($_GET['filter'] == "new")  $sort = "ORDER BY AnswerToQuestion.answer_id DESC";
    if ($_GET['filter'] == "old")  $sort = "ORDER BY AnswerToQuestion.answer_id ASC";
}

/* ================= FETCH ANSWERS ================= */
$a = mysqli_query(
    $conn,
    "SELECT AnswerToQuestion.*, 
        studentdata.name AS student_name, 
        facultydata.name AS faculty_name,
        AVG(answer_rating.rating) AS avg_rating

        FROM AnswerToQuestion

        LEFT JOIN studentdata 
        ON studentdata.enrollment = AnswerToQuestion.user_enrollment

        LEFT JOIN facultydata 
        ON facultydata.email = AnswerToQuestion.user_enrollment

        LEFT JOIN answer_rating 
        ON AnswerToQuestion.answer_id = answer_rating.answer_id

        WHERE question_id='$qid'

        GROUP BY AnswerToQuestion.answer_id
        $sort"
);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>View Question</title>

    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-body-tertiary">

<?php include("../../_Navbar.php"); ?>

<div class="container py-5">

    <!-- ================= QUESTION ================= -->
    <div class="card shadow border-0 rounded-4 mb-4 p-4">

        <h4 class="fw-bold mb-2">
            <i class="bi bi-question-circle me-2"></i>
            <?php echo $row['question']; ?>
        </h4>

        <small class="text-muted">Discussion thread</small>

    </div>

    <!-- ================= ANSWER BOX ================= -->
    <div class="card shadow border-0 rounded-4 mb-4">

        <div class="card-body">

            <h5 class="mb-3">
                <i class="bi bi-pencil-square me-2"></i> Write Your Answer
            </h5>

            <form method="post">

                <div class="form-floating mb-3">
                    <textarea name="answer"
                              class="form-control"
                              id="answerBox"
                              placeholder="Write answer"
                              style="height: 120px"
                              required></textarea>
                    <label for="answerBox">Type your answer here...</label>
                </div>

                <div class="d-flex gap-2">

                    <button name="postAnswer"
                            class="btn btn-success rounded-pill px-4">
                        <i class="bi bi-send me-1"></i> Post
                    </button>

                    <a href="StudentForum.php"
                       class="btn btn-outline-dark rounded-pill px-4">
                        Back
                    </a>

                </div>

            </form>

        </div>

    </div>

    <!-- ================= FILTER ================= -->
    <div class="d-flex justify-content-end mb-4">

        <form method="get" class="d-flex gap-2">

            <input type="hidden" name="qid" value="<?php echo $qid; ?>">

            <select name="filter"
                    class="form-select rounded-pill"
                    onchange="this.form.submit()">

                <option value="">Sort</option>
                <option value="high">⭐ Highest Rated</option>
                <option value="low">⭐ Lowest Rated</option>
                <option value="new">🆕 Newest</option>
                <option value="old">📜 Oldest</option>

            </select>

        </form>

    </div>

    <!-- ================= ANSWERS ================= -->
    <?php if (mysqli_num_rows($a) == 0) { ?>
        <div class="alert alert-info text-center">
            No answers yet. Be the first to answer!
        </div>
    <?php } ?>

    <?php while ($r = mysqli_fetch_assoc($a)) { ?>

        <?php
        $isStudent = $r['student_name'] != NULL;
        $name = $isStudent ? $r['student_name'] : $r['faculty_name'];
        $badgeColor = $isStudent ? "primary" : "success";

        $avg = round($r['avg_rating']);
        if ($avg == 3) $label = "Very Good";
        elseif ($avg == 2) $label = "Good";
        elseif ($avg == 1) $label = "Poor";
        else $label = "No Rating";
        ?>

        <div class="card shadow-sm border-0 rounded-4 mb-3">

            <div class="card-body">

                <!-- USER INFO -->
                <div class="d-flex justify-content-between align-items-center mb-2">

                    <div class="d-flex align-items-center gap-2">

                        <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png"
                             width="35"
                             class="rounded-circle">

                        <div>
                            <a href="Profile.php?id=<?php echo $r['user_enrollment']; ?>"
                               class="fw-semibold text-dark text-decoration-none">
                                <?php echo $name; ?>
                            </a>

                            <span class="badge bg-<?php echo $badgeColor; ?>">
                                <?php echo $isStudent ? "Student" : "Faculty"; ?>
                            </span>
                        </div>

                    </div>

                    <span class="badge bg-light text-dark">
                        ⭐ <?php echo $label; ?>
                    </span>

                </div>

                <!-- ANSWER -->
                <p class="mb-3">
                    <?php echo $r['answer']; ?>
                </p>

                <!-- RATING -->
                <form method="post">

                    <input type="hidden" name="answer_id" value="<?php echo $r['answer_id']; ?>">

                    <div class="d-flex flex-wrap align-items-center gap-3">

                        <label class="form-check-label">
                            <input type="radio" name="rating" value="3" required> ⭐⭐⭐ Very Good
                        </label>

                        <label class="form-check-label">
                            <input type="radio" name="rating" value="2"> ⭐⭐ Good
                        </label>

                        <label class="form-check-label">
                            <input type="radio" name="rating" value="1"> ⭐ Poor
                        </label>

                        <button name="rateAnswer"
                                class="btn btn-warning btn-sm rounded-pill">
                            Rate
                        </button>

                    </div>

                </form>

            </div>

        </div>

    <?php } ?>

</div>

<!-- Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>