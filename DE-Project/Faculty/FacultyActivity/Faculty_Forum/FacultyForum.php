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

<body class="bg-body-tertiary">

<?php include("../../loader.php"); ?>
<?php include("../../_Navbar.php"); ?>

<div class="container py-5">

    <!-- ================= HEADER ================= -->
    <div class="card shadow border-0 rounded-4 p-4 mb-4">

        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">

            <h4 class="fw-bold mb-0">
                <i class="bi bi-chat-dots me-2"></i> Faculty Forum
            </h4>

            <div class="d-flex flex-wrap gap-2">

                <!-- Search -->
                <input type="text"
                       id="searchInput"
                       class="form-control rounded-pill"
                       placeholder="Search question..."
                       oninput="searchQuestion()">

                <!-- Filter -->
                <form method="get">
                    <select name="filter"
                            class="form-select rounded-pill"
                            onchange="this.form.submit()">

                        <option value="">Filter</option>
                        <option value="popular_high" <?php if ($filter == "popular_high") echo "selected"; ?>>Highest Popular</option>
                        <option value="popular_low" <?php if ($filter == "popular_low") echo "selected"; ?>>Lowest Popular</option>
                        <option value="new" <?php if ($filter == "new") echo "selected"; ?>>Newest</option>
                        <option value="old" <?php if ($filter == "old") echo "selected"; ?>>Oldest</option>
                        <option value="own" <?php if ($filter == "own") echo "selected"; ?>>My Questions</option>

                    </select>
                </form>

                <!-- Ask -->
                <a href="FacultyAskQuestion.php"
                   onclick="handleNavigation(event, this.href)"
                   class="btn btn-success rounded-pill px-4">
                    <i class="bi bi-plus-circle me-1"></i> Ask
                </a>

            </div>

        </div>

    </div>


    <!-- ================= QUESTIONS ================= -->
    <div id="questionContainer">

        <?php while ($row = mysqli_fetch_assoc($q)) { ?>

            <?php
            $isStudent = !empty($row['student_name']);
            $name = $isStudent ? $row['student_name'] : $row['faculty_name'];
            $role = $isStudent ? "Student" : "Faculty";
            $badgeColor = $isStudent ? "primary" : "success";
            ?>

            <!-- Question Card -->
            <div class="card shadow-sm border-0 rounded-4 mb-3 question-card">

                <div class="card-body">

                    <!-- TOP -->
                    <div class="d-flex justify-content-between align-items-center mb-2">

                        <!-- User -->
                        <div class="d-flex align-items-center gap-2">

                            <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png"
                                 width="35"
                                 class="rounded-circle">

                            <div>
                                <a href="Profile.php?id=<?php echo urlencode($row['user_enrollment']); ?>"
                                   onclick="handleNavigation(event, this.href)"
                                   class="fw-semibold text-dark text-decoration-none">
                                    <?php echo $name; ?>
                                </a>

                                <span class="badge bg-<?php echo $badgeColor; ?> ms-1">
                                    <?php echo $role; ?>
                                </span>
                            </div>

                        </div>

                        <!-- Count -->
                        <span class="badge bg-light text-dark">
                            <?php echo $row['answer_count']; ?> Answers
                        </span>

                    </div>

                    <!-- QUESTION -->
                    <p class="mb-3 fw-semibold">
                        <?php echo $row['question']; ?>
                    </p>

                    <!-- ACTION -->
                    <div class="d-flex justify-content-end">
                        <a href="View_question.php?qid=<?php echo $row['question_id']; ?>"
                           onclick="handleNavigation(event, this.href)"
                           class="btn btn-dark btn-sm rounded-pill px-3">
                            View Answers
                        </a>
                    </div>

                </div>

            </div>

        <?php } ?>

    </div>

    <!-- NO RESULT -->
    <div id="noResult"
         class="text-center text-danger fs-5 mt-3"
         style="display:none;">
        No matching questions found
    </div>

</div>

<!-- Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<script>
function handleNavigation(event, url) {
    event.preventDefault();

    const loader = document.getElementById("loader-wrapper");
    const bar = document.getElementById("progress-bar");

    if (loader && bar) {
        loader.classList.remove("d-none");
        bar.style.width = "0%";

        let progress = 0;

        let interval = setInterval(() => {
            progress += 25;
            bar.style.width = progress + "%";

            if (progress >= 90) clearInterval(interval);
        }, 120);
    }

    setTimeout(() => {
        window.location.href = url;
    }, 300);
}

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