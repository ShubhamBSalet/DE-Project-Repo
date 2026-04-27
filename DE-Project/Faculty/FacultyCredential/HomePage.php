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

<body class="bg-body-tertiary">

<?php include("../loader.php"); ?>
<?php include("../_Navbar.php"); ?>

<div class="container py-5">

    <!-- ================= HEADER ================= -->
    <div class="card shadow border-0 rounded-4 p-4 mb-4">
        <h3 class="fw-bold mb-1">
            <i class="bi bi-speedometer2 me-2"></i> Faculty Dashboard
        </h3>
        <p class="text-muted mb-0">Overview of your profile, events, and forum activity</p>
    </div>


    <!-- ================= PROFILE + STATS ================= -->
    <div class="row g-4 mb-5">

        <!-- Profile -->
        <div class="col-lg-4">

            <div class="card shadow border-0 rounded-4 text-center p-4 h-100">

                <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png"
                     class="rounded-circle mx-auto mb-3"
                     width="110">

                <h5 class="fw-bold mb-1"><?php echo $row['name']; ?></h5>
                <small class="text-muted"><?php echo $row['post']; ?></small>

                <span class="badge bg-success mt-2">Faculty</span>

                <hr>

                <div class="text-start small">

                    <p><i class="bi bi-envelope me-2"></i><?php echo $row['email']; ?></p>
                    <p><i class="bi bi-diagram-3 me-2"></i><?php echo $row['branch']; ?></p>
                    <p><i class="bi bi-phone me-2"></i><?php echo $row['mobile']; ?></p>

                </div>

            </div>

        </div>

        <!-- Stats -->
        <div class="col-lg-8">

            <div class="row g-3">

                <div class="col-md-6">
                    <div class="card shadow-sm border-0 rounded-4 p-3">
                        <small class="text-muted">Recent Events</small>
                        <h3 class="fw-bold text-primary"><?php echo mysqli_num_rows($eventResult); ?></h3>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card shadow-sm border-0 rounded-4 p-3">
                        <small class="text-muted">Top Questions</small>
                        <h3 class="fw-bold text-success"><?php echo mysqli_num_rows($questionResult); ?></h3>
                    </div>
                </div>

            </div>

        </div>

    </div>


    <!-- ================= EVENTS ================= -->
    <div class="mb-5">

        <h4 class="fw-bold mb-3">
            <i class="bi bi-calendar-event me-2"></i> Latest Events
        </h4>

        <div class="row g-4">

            <?php mysqli_data_seek($eventResult, 0); while ($event = mysqli_fetch_assoc($eventResult)) { ?>

                <div class="col-md-6 col-lg-4">

                    <div class="card shadow-sm border-0 rounded-4 h-100 p-3">

                        <h6 class="fw-bold"><?php echo $event['event_name']; ?></h6>

                        <small class="text-muted d-block mb-2">
                            <i class="bi bi-clock me-1"></i>
                            <?php echo date("d M Y, h:i A", strtotime($event['event_datetime'])); ?>
                        </small>

                        <p class="small mb-2">
                            <i class="bi bi-geo-alt me-1"></i>
                            <?php echo $event['event_place']; ?>
                        </p>

                        <a href="<?php echo $event['event_form_link']; ?>"
                           target="_blank"
                           class="btn btn-primary rounded-pill mt-auto">
                            View / Register
                        </a>

                    </div>

                </div>

            <?php } ?>

        </div>

    </div>


    <!-- ================= QUESTIONS ================= -->
    <div>

        <h4 class="fw-bold mb-3">
            <i class="bi bi-chat-dots me-2"></i> Top Questions
        </h4>

        <?php mysqli_data_seek($questionResult, 0); while ($q = mysqli_fetch_assoc($questionResult)) { ?>

            <div class="card shadow-sm border-0 rounded-4 mb-3 p-3">

                <p class="fw-semibold mb-2"><?php echo $q['question']; ?></p>

                <div class="d-flex justify-content-between align-items-center">

                    <span class="badge bg-success">
                        <?php echo $q['answer_count']; ?> Answers
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

<!-- Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
function handleNavigation(event, url) {
    event.preventDefault();

    const loader = document.getElementById("loader-wrapper");

    if (loader) {
        loader.classList.remove("d-none");
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