<?php
session_start();

// ✅ Check admin login
if (!isset($_SESSION['AdminLoggedin']) || $_SESSION['AdminLoggedin'] != true) {
    header("Location: ../_NotLoggedIn.php");
    exit();
}

include("../../_DBConnect.php");

/* ================= GET USER ================= */
$user = $_SESSION['email'];

/* ================= GET QID ================= */
if (!isset($_GET['qid'])) {
    header("Location: AdminForum.php");
    exit();
}

$qid = $_GET['qid'];

/* ================= POST ANSWER ================= */
if (isset($_POST['postAnswer'])) {

    $ans = mysqli_real_escape_string($conn, $_POST['answer']);

    mysqli_query(
        $conn,
        "INSERT INTO AnswerToQuestion (question_id,user_enrollment,answer)
         VALUES('$qid','$user','$ans')"
    );
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
    if ($_GET['filter'] == "new")  $sort = "ORDER BY AnswerToQuestion.answer_id DESC";
    if ($_GET['filter'] == "old")  $sort = "ORDER BY AnswerToQuestion.answer_id ASC";
}

/* ================= FETCH ANSWERS ================= */
$a = mysqli_query(
    $conn,
    "SELECT AnswerToQuestion.*, 
     studentdata.name AS student_name, 
     facultydata.name AS faculty_name

     FROM AnswerToQuestion

     LEFT JOIN studentdata 
     ON studentdata.enrollment = AnswerToQuestion.user_enrollment

     LEFT JOIN facultydata 
     ON facultydata.email = AnswerToQuestion.user_enrollment

     WHERE question_id='$qid'
     $sort"
);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>View Question</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <?php include("../_Navbar.php"); ?>

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

                    <a href="./AdminForum.php" class="btn btn-dark ms-2">
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

                <option value="new" <?php if (@$_GET['filter'] == "new") echo "selected"; ?>>
                    New
                </option>

                <option value="old" <?php if (@$_GET['filter'] == "old") echo "selected"; ?>>
                    Old
                </option>

            </select>
        </form>

        <!-- ANSWERS -->
        <?php if (mysqli_num_rows($a) == 0) { ?>
            <div class="alert alert-info">
                No answers yet.
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

                    </div>

                    <!-- ANSWER -->
                    <p class="mb-3"><?php echo $r['answer']; ?></p>

                    <!-- ACTION -->
                    <div class="d-flex justify-content-end">

                        <a href="delete_answer.php?aid=<?php echo $r['answer_id']; ?>&qid=<?php echo $qid; ?>"
                            class="btn btn-danger btn-sm rounded-pill"
                            onclick="return confirm('Delete this answer?');">
                            Delete
                        </a>

                    </div>

                </div>

            </div>

        <?php } ?>

    </div>

</body>

</html>