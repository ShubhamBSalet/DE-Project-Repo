<?php
session_start();
include("../../../_DBConnect.php");

/* ================= LOGIN CHECK ================= */
if (!isset($_SESSION['facultyLoggedin']) || $_SESSION['facultyLoggedin'] !== true) {
    header("Location: ../../_NotLoggedIn.php");
    exit();
}

/* ================= GET USER ================= */
$user = $_SESSION['email']; // faculty only

/* ================= GET QID ================= */
if (!isset($_GET['qid'])) {
    header("Location: FacultyForum.php");
    exit();
}

$qid = $_GET['qid'];

/* ================= POST ANSWER ================= */
if (isset($_POST['postAnswer'])) {

    $ans = mysqli_real_escape_string($conn, $_POST['answer']);

    mysqli_query($conn,
        "INSERT INTO AnswerToQuestion (question_id,user_enrollment,answer)
         VALUES('$qid','$user','$ans')");
}

/* ================= RATE ANSWER ================= */
if (isset($_POST['rateAnswer'])) {

    $answer_id = $_POST['answer_id'];
    $rating = $_POST['rating'];

    $check = mysqli_query($conn,
        "SELECT * FROM answer_rating 
         WHERE answer_id='$answer_id' 
         AND user_enrollment='$user'");

    if (mysqli_num_rows($check) > 0) {

        mysqli_query($conn,
            "UPDATE answer_rating 
             SET rating='$rating' 
             WHERE answer_id='$answer_id' 
             AND user_enrollment='$user'");

    } else {

        mysqli_query($conn,
            "INSERT INTO answer_rating (answer_id,user_enrollment,rating)
             VALUES ('$answer_id','$user','$rating')");
    }
}

/* ================= FETCH QUESTION ================= */
$q = mysqli_query($conn,
    "SELECT * FROM questionask WHERE question_id='$qid'");
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
$a = mysqli_query($conn,
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

<body class="bg-light">

<?php include("../../_Navbar.php"); ?>

<div class="container py-5">

    <!-- QUESTION -->
    <div class="card shadow-sm rounded-4 mb-4">
        <div class="card-body">
            <h4 class="fw-bold"><?php echo $row['question']; ?></h4>
        </div>
    </div>

    <!-- ANSWER FORM -->
    <div class="card shadow-sm rounded-4 mb-4">
        <div class="card-body">

            <h5 class="mb-3">Write Your Answer</h5>

            <form method="post">

                <textarea name="answer"
                    class="form-control mb-3"
                    rows="4"
                    placeholder="Write your answer..."
                    required></textarea>

                <button name="postAnswer" class="btn btn-success">
                    Post Answer
                </button>

                <a href="./FacultyForum.php" class="btn btn-dark ms-2">
                    Back
                </a>

            </form>

        </div>
    </div>

    <!-- FILTER -->
    <form method="get" class="mb-4">

        <input type="hidden" name="qid" value="<?php echo $qid; ?>">

        <select name="filter"
                class="form-select w-25"
                onchange="this.form.submit()">

            <option value="">Filter</option>

            <option value="high" <?php if (@$_GET['filter']=="high") echo "selected"; ?>>
                Highest Rated
            </option>

            <option value="low" <?php if (@$_GET['filter']=="low") echo "selected"; ?>>
                Lowest Rated
            </option>

            <option value="new" <?php if (@$_GET['filter']=="new") echo "selected"; ?>>
                New
            </option>

            <option value="old" <?php if (@$_GET['filter']=="old") echo "selected"; ?>>
                Old
            </option>

        </select>

    </form>

    <!-- ANSWERS -->

    <?php if (mysqli_num_rows($a) == 0) { ?>
        <div class="alert alert-info">
            No answers yet. Be the first to answer!
        </div>
    <?php } ?>

    <?php while ($r = mysqli_fetch_assoc($a)) { ?>

        <?php
        $isStudent = $r['student_name'] != NULL;
        $name = $isStudent ? $r['student_name'] : $r['faculty_name'];
        $border = $isStudent ? "border-primary" : "border-success";
        ?>

        <div class="card shadow-sm rounded-4 mb-3 border-3 <?php echo $border; ?>">

            <div class="card-body">

                <!-- TOP -->
                <div class="d-flex justify-content-between align-items-center mb-2">

                    <h5 class="mb-0">
                        <a href="Profile.php?id=<?php echo $r['user_enrollment']; ?>"
                           class="text-decoration-none fw-semibold">
                            <?php echo $name; ?>
                        </a>

                        <span class="badge bg-secondary ms-2">
                            <?php echo $isStudent ? "Student" : "Faculty"; ?>
                        </span>
                    </h5>

                    <span class="badge bg-light text-dark">
                        ⭐ <?php echo round($r['avg_rating'], 1); ?>
                    </span>

                </div>

                <!-- ANSWER -->
                <p class="mb-3"><?php echo $r['answer']; ?></p>

                <!-- RATING -->
                <form method="post">

                    <input type="hidden" name="answer_id" value="<?php echo $r['answer_id']; ?>">

                    <div class="d-flex align-items-center gap-3">

                        <input type="range"
                               name="rating"
                               min="0"
                               max="5"
                               step="1"
                               value="0"
                               class="form-range w-50"
                               oninput="ratingValue<?php echo $r['answer_id']; ?>.innerText = this.value">

                        <span id="ratingValue<?php echo $r['answer_id']; ?>" class="fw-bold">
                            0
                        </span>

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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>