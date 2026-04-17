<?php
session_start();

//****************if faculty not logged in****************
if (!isset($_SESSION['facultyLoggedin']) || $_SESSION['facultyLoggedin'] != true) {
    header("Location: ../../_NotLoggedIn.php");
    exit();
}

//****************if student logged in****************
if (isset($_SESSION['studentLoggedin']) && $_SESSION['studentLoggedin'] == true) {
    header("Location: /DE-Project/Student/StudentCredential/HomePage.php");
    exit();
}

include("../../_DBConnect.php");

// get faculty email
$id = $_SESSION['email'];

// fetch faculty data
$query = "SELECT * FROM facultydata WHERE email='$id'";
$q = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($q);

// ===== Latest 5 Events =====
$eventQuery = "SELECT * FROM eventpost ORDER BY event_datetime DESC LIMIT 5";
$eventResult = mysqli_query($conn, $eventQuery);

// ===== Top 2 Questions =====
$questionQuery = "
SELECT questionask.*, 
COUNT(AnswerToQuestion.answer_id) AS answer_count
FROM questionask
LEFT JOIN AnswerToQuestion 
ON AnswerToQuestion.question_id = questionask.question_id
GROUP BY questionask.question_id
ORDER BY answer_count DESC
LIMIT 2
";
$questionResult = mysqli_query($conn, $questionQuery);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Faculty Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <?php include("../loader.php"); ?> <!-- ADD THIS -->
    <?php include("../_Navbar.php"); ?>

    <div class="container py-5">

        <!-- ================= PROFILE ================= -->
        <div class="row justify-content-center mb-5">

            <div class="col-lg-5">

                <div class="card shadow-lg border-0 rounded-4">

                    <div class="card-header bg-success text-white text-center rounded-top-4">
                        <h4 class="mb-0">Faculty Profile</h4>
                    </div>

                    <div class="card-body text-center p-5">

                        <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png"
                            class="rounded-circle img-fluid w-50 mb-3">

                        <h4 class="fw-bold"><?php echo $row['name']; ?></h4>

                        <span class="badge bg-success mb-3">Faculty</span>

                        <hr>

                        <div class="text-start">
                            <p><b>Email:</b> <?php echo $row['email']; ?></p>
                            <p><b>Post:</b> <?php echo $row['post']; ?></p>
                            <p><b>Branch:</b> <?php echo $row['branch']; ?></p>
                            <p><b>Mobile:</b> <?php echo $row['mobile']; ?></p>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- ================= EVENTS ================= -->
        <div class="mb-5">

            <div class="d-flex justify-content-between mb-3">
                <h4 class="fw-bold">Latest Events</h4>
            </div>
            <hr>

            <div class="row g-4">

                <?php while ($event = mysqli_fetch_assoc($eventResult)) { ?>

                    <div class="col-md-4">

                        <div class="card shadow-sm rounded-4 h-100 p-3">

                            <h6 class="fw-bold">
                                <?php echo $event['event_name']; ?>
                            </h6>

                            <small class="text-muted d-block mb-2">
                                <?php echo date("d M Y, h:i A", strtotime($event['event_datetime'])); ?>
                            </small>

                            <p><b>Place:</b> <?php echo $event['event_place']; ?></p>
                            <a href="<?php echo $event['event_form_link']; ?>"
                                target="_blank"
                                class="btn btn-primary w-100 rounded-pill mb-2">
                                View / Register
                            </a>

                        </div>

                    </div>

                <?php } ?>

            </div>

        </div>


        <!-- ================= TOP QUESTIONS ================= -->
        <div>

            <div class="d-flex justify-content-between mb-3">
                <h4 class="fw-bold">Top Questions</h4>
            </div>
            <hr>

            <?php while ($q = mysqli_fetch_assoc($questionResult)) { ?>

                <div class="card shadow-sm mb-3 p-3 rounded-4">

                    <p class="fw-semibold mb-2">
                        <?php echo $q['question']; ?>
                    </p>

                    <div class="d-flex justify-content-between align-items-center">

                        <span class="badge bg-success">
                            Answers: <?php echo $q['answer_count']; ?>
                        </span>

                        <a href="../FacultyActivity/Faculty_Forum/View_question.php?qid=<?php echo $q['question_id']; ?>"
                            onclick="handleNavigation(event, this.href)"
                            class="btn btn-sm btn-dark rounded-pill">
                            View
                        </a>

                    </div>

                </div>

            <?php } ?>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function handleNavigation(event, url) {
            event.preventDefault();

            const loader = document.getElementById("loader-wrapper");

            if (loader) {
                loader.classList.remove("d-none");

                // reset progress (optional but better UX)
                let bar = document.getElementById("progress-bar");
                if (bar) bar.style.width = "0%";
            }

            setTimeout(() => {
                window.location.href = url;
            }, 300);
        }
    </script>
</body>

</html>