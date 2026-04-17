<?php
session_start();
include("../../../_DBConnect.php");

//************check student logged not in************
if (!isset($_SESSION['studentLoggedin']) || $_SESSION['studentLoggedin'] != true) {
    header("Location: /DE-Project/_NotLoggedIn.php");
    exit();
}

//************get filter value from dropdown
$filter = $_GET['filter'] ?? "";

//************$condition is for viewing own ask questions************
$condition = "";

//************for filter dropdown sorting************
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
    $enroll = $_SESSION['enrollment'];
    $condition = "WHERE questionask.user_enrollment='$enroll'";
}

// fetch all column from questionask table, Fetches names from both tables, Counts how many answers each question has from the questionask table
// If question is asked by a student → data comes, user_enrollment matches student enrollment
// If question is asked by faculty → data comes, user_enrollment matches faculty email
// Joins answers table to get all answers of each question for Counts how many answers each question has
$query = "SELECT questionask.*, studentdata.name AS student_name, facultydata.name AS faculty_name,
                COUNT(AnswerToQuestion.answer_id) AS answer_count FROM questionask 
                LEFT JOIN studentdata ON studentdata.enrollment = questionask.user_enrollment
                LEFT JOIN facultydata ON facultydata.email = questionask.user_enrollment
                LEFT JOIN AnswerToQuestion ON AnswerToQuestion.question_id = questionask.question_id
                $condition GROUP BY questionask.question_id $order";

$q = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Student Forum</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <?php include("../../loader.php"); ?>
    <?php include("../../_Navbar.php"); ?>

    <div class="container py-5">

        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

            <h3 class="fw-bold mb-0">Student Forum</h3>

            <div class="d-flex flex-wrap gap-2">

                <!--============searching questions============-->
                <input type="text" id="searchInput" class="form-control rounded-pill" placeholder="Search question..."
                    oninput="searchQuestion()">

                <!--============FILTER(sorting)============-->
                <form method="get">
                    <select name="filter" class="form-select rounded-pill" onchange="this.form.submit()">

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

                <!--==========Button to ask question==========-->
                <a href="StudentAskQuestion.php" class="btn btn-primary rounded-pill px-4">Ask Question</a>

            </div>

        </div>

        <!--==========listing all questions==========-->
        <div id="questionContainer">

            <!--***********get rows(data) from database in an array formate***********-->
            <?php while ($row = mysqli_fetch_assoc($q)) { ?>

                <?php
                // check question asked by student -> student_name(studentdata.name)
                $isStudent = !empty($row['student_name']);

                // store the name of student | faculty
                $name = $isStudent ? $row['student_name'] : $row['faculty_name'];

                // store the role of student | faculty
                $role = $isStudent ? "Student" : "Faculty";

                // if student then blue(primary) | faculty green(success)
                $border = $isStudent ? "border-primary" : "border-success";
                ?>

                <!--=========border according to role=========-->
                <div class="card shadow-sm border-3 rounded-4 mb-4 <?php echo $border; ?> question-card">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center mb-2">

                            <!--=========link for redirect Profile.php for a particluar student according to enrollment=========-->
                            <div>
                                <a href="Profile.php?id=<?php echo urlencode($row['user_enrollment']); ?>"
                                    onclick="handleNavigation(event, this.href)"
                                    class="fw-semibold text-decoration-none">
                                    <?php echo $name; ?>
                                </a>


                                <!--=========displaying user role(student | faculty)=========-->
                                <span class="badge bg-secondary ms-2">
                                    <?php echo $role; ?>
                                </span>
                            </div>

                            <!--=========show total answer for a particular question=========-->
                            <span class="badge bg-light text-dark">
                                Response - <?php echo $row['answer_count']; ?>
                            </span>

                        </div>

                        <!--=========display question=========-->
                        <p class="mb-3">
                            <?php echo $row['question']; ?>
                        </p>

                        <!--=========link to redirect View_question.php according to questio id(qid)=========-->
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

        <!--=========if searched input not found=========-->
        <div id="noResult" class="text-center text-danger fs-4 mt-3" style="display:none;">
            No matching questions found
        </div>

    </div>

    <script>
        function handleNavigation(event, url) {
            event.preventDefault(); // stop instant redirect

            const loader = document.getElementById("loader-wrapper");
            if (loader) {
                loader.classList.remove("d-none");
            }

            // small delay so loader is visible
            setTimeout(() => {
                window.location.href = url;
            }, 300);
        }
    </script>

    <!--========JS for searching question========-->
    <script>
        function searchQuestion() {

            //get user input with lowercase
            let input = document.getElementById("searchInput").value.toLowerCase();

            //Selects all elements with class .question-card -> each card = one question
            let cards = document.querySelectorAll(".question-card");

            //tracking variable for searched matched or not 
            let found = false;

            //loop through the card
            cards.forEach(function(card) {

                // get full text inside the card & convert it to lowercase
                let text = card.innerText.toLowerCase();

                // if searched text found
                if (text.includes(input)) {
                    // show card
                    card.style.display = "";
                    // mark as found
                    found = true;
                } else {
                    // hide card
                    card.style.display = "none";
                }

            });

            // If match found → hide message, If no match → show message
            document.getElementById("noResult").style.display = found ? "none" : "block";
        }
    </script>

</body>

</html>