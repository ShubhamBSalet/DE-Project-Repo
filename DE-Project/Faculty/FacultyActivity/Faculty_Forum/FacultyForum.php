<?php
session_start();
include("../../../_DBConnect.php");

/* LOGIN CHECK */
if (!isset($_SESSION['facultyLoggedin']) || $_SESSION['facultyLoggedin'] != true) {
    header("Location: /DE-Project/_NotLoggedIn.php");
    exit();
}


/* ================= FILTER ================= */
$filter = $_GET['filter'] ?? "";

$where = "";
$order = "ORDER BY questionask.question_id DESC";

if ($filter == "popular_high") {
    $order = "ORDER BY answer_count DESC";
} elseif ($filter == "popular_low") {
    $order = "ORDER BY answer_count ASC";
} elseif ($filter == "new") {
    $order = "ORDER BY questionask.question_id DESC";
} elseif ($filter == "old") {
    $order = "ORDER BY questionask.question_id ASC";
} elseif ($filter == "own") {
    $id = $_SESSION['email'];
    $where = "WHERE questionask.user_enrollment='$id'";
}

/* ================= QUERY ================= */
$query = "
SELECT questionask.*, 
studentdata.name AS student_name, 
facultydata.name AS faculty_name,
COUNT(AnswerToQuestion.answer_id) AS answer_count

FROM questionask

LEFT JOIN studentdata 
ON studentdata.enrollment = questionask.user_enrollment

LEFT JOIN facultydata 
ON facultydata.email = questionask.user_enrollment

LEFT JOIN AnswerToQuestion 
ON AnswerToQuestion.question_id = questionask.question_id

$where

GROUP BY questionask.question_id
$order
";

$q = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Faculty Forum</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<?php include("../../_Navbar.php"); ?>

<div class="container py-5">

    <!-- HEADER -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

        <h3 class="fw-bold mb-0">Faculty Forum</h3>

        <div class="d-flex flex-wrap gap-2">

            

            <!-- 🔍 SEARCH (NO RELOAD) -->
            <input type="text"
                id="searchInput"
                class="form-control rounded-pill"
                placeholder="Search question..."
                oninput="searchQuestion()">

            <!-- 🎯 FILTER -->
            <form method="get">
                <select name="filter"
                    class="form-select rounded-pill"
                    onchange="this.form.submit()">

                    <option value="">Filter</option>

                    <option value="popular_high" <?php if ($filter == "popular_high") echo "selected"; ?>>
                        Highest Popular
                    </option>

                    <option value="popular_low" <?php if ($filter == "popular_low") echo "selected"; ?>>
                        Lowest Popular
                    </option>

                    <option value="new" <?php if ($filter == "new") echo "selected"; ?>>
                        Newest
                    </option>

                    <option value="old" <?php if ($filter == "old") echo "selected"; ?>>
                        Oldest
                    </option>

                    <option value="own" <?php if ($filter == "own") echo "selected"; ?>>
                        My Questions
                    </option>

                </select>
            </form>

            <!-- Ask -->
            <a href="FacultyAskQuestion.php"
                class="btn btn-success rounded-pill px-4">
                Ask Question
            </a>

        </div>

    </div>

    <!-- QUESTIONS -->
    <div id="questionContainer">

        <?php while ($row = mysqli_fetch_assoc($q)) { ?>

            <?php
            $isStudent = !empty($row['student_name']);
            $name = $isStudent ? $row['student_name'] : $row['faculty_name'];
            $role = $isStudent ? "Student" : "Faculty";
            $border = $isStudent ? "border-primary" : "border-success";
            ?>

            <!-- ✅ IMPORTANT CLASS -->
            <div class="card shadow-sm border-3 rounded-4 mb-4 <?php echo $border; ?> question-card">

                <div class="card-body">

                    <!-- TOP -->
                    <div class="d-flex justify-content-between align-items-center mb-2">

                        <div>
                            <a href="Profile.php?id=<?php echo urlencode($row['user_enrollment']); ?>"
                                class="fw-semibold text-decoration-none">
                                <?php echo $name; ?>
                            </a>

                            <span class="badge bg-secondary ms-2">
                                <?php echo $role; ?>
                            </span>
                        </div>

                        <span class="badge bg-light text-dark">
                            Response - <?php echo $row['answer_count']; ?>
                        </span>

                    </div>

                    <!-- QUESTION -->
                    <p class="mb-3">
                        <?php echo $row['question']; ?>
                    </p>

                    <!-- ACTION -->
                    <div class="d-flex justify-content-end">
                        <a href="View_question.php?qid=<?php echo $row['question_id']; ?>"
                            class="btn btn-dark btn-sm rounded-pill px-3">
                            View Answers
                        </a>
                    </div>

                </div>

            </div>

        <?php } ?>

    </div>

    <!-- ❌ No Result -->
    <div id="noResult" class="text-center text-danger fs-4 mt-3" style="display:none;">
        No matching questions found
    </div>

</div>

<!-- 🔥 SEARCH SCRIPT -->
<script>
function searchQuestion() {

    let input = document.getElementById("searchInput").value.toLowerCase();
    let cards = document.querySelectorAll(".question-card");
    let found = false;

    cards.forEach(function(card) {

        let text = card.innerText.toLowerCase();

        if (text.includes(input)) {
            card.style.display = "";
            found = true;
        } else {
            card.style.display = "none";
        }

    });

    document.getElementById("noResult").style.display = found ? "none" : "block";
}
</script>

</body>
</html>