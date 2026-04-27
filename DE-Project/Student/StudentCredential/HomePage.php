<?php
session_start();

//****************if student not logged in****************
if (!isset($_SESSION['studentLoggedin'])) {
    header("Location: /DE-Project/_NotLoggedIn.php");
    exit();
}

//****************if faculty logged in****************
if (isset($_SESSION['facultyLoggedin']) && $_SESSION['facultyLoggedin'] == true) {
    header("Location: /DE-Project/Faculty/FacultyCredential/HomePage.php");
    exit();
}

include("../../_DBConnect.php");

// get session user email from loginOTP
$id = $_SESSION['email'];

// fetch student data according to email
$query = "SELECT * FROM studentdata WHERE email='$id'";
$q = mysqli_query($conn, $query);

// get row data in an array formate 
$row = mysqli_fetch_assoc($q);

// query for Latest 5 Events
$eventQuery = "SELECT * FROM eventpost ORDER BY event_datetime DESC LIMIT 5";
$eventResult = mysqli_query($conn, $eventQuery);

// quey for Top 2 Questions 
$questionQuery = "SELECT questionask.*, COUNT(AnswerToQuestion.answer_id) AS answer_count FROM questionask
                        LEFT JOIN AnswerToQuestion ON AnswerToQuestion.question_id = questionask.question_id
                        GROUP BY questionask.question_id ORDER BY answer_count DESC LIMIT 2";

$questionResult = mysqli_query($conn, $questionQuery);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-body-tertiary">

<?php include("../loader.php"); ?>
<?php include("../_Navbar.php"); ?>

<div class="container py-5">

    <!-- ================= PROFILE ================= -->
    <div class="row g-4 mb-5">

        <!-- Profile Card -->
        <div class="col-lg-4">

            <div class="card shadow border-0 rounded-4 text-center p-4 h-100">

                <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png"
                     class="rounded-circle mx-auto mb-3"
                     width="120">

                <h5 class="fw-bold mb-1"><?php echo $row['name']; ?></h5>
                <small class="text-muted mb-3"><?php echo $row['branch']; ?></small>

                <hr>

                <div class="text-start small">

                    <p><i class="bi bi-person-badge me-2"></i><b>Enrollment:</b> <?php echo $row['enrollment']; ?></p>
                    <p><i class="bi bi-envelope me-2"></i><b>Email:</b> <?php echo $row['email']; ?></p>
                    <p><i class="bi bi-phone me-2"></i><b>Mobile:</b> <?php echo $row['mobile']; ?></p>

                </div>

            </div>

        </div>

        <!-- Quick Info Cards -->
        <div class="col-lg-8">

            <div class="row g-3">

                <div class="col-md-6">
                    <div class="card shadow-sm border-0 rounded-4 p-3">
                        <h6 class="text-muted">Total Events</h6>
                        <h3 class="fw-bold text-primary"><?php echo mysqli_num_rows($eventResult); ?></h3>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card shadow-sm border-0 rounded-4 p-3">
                        <h6 class="text-muted">Top Questions</h6>
                        <h3 class="fw-bold text-success"><?php echo mysqli_num_rows($questionResult); ?></h3>
                    </div>
                </div>

            </div>

        </div>

    </div>


    <!-- ================= EVENTS ================= -->
    <div class="mb-5">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold">
                <i class="bi bi-calendar-event me-2"></i> Latest Events
            </h4>
        </div>

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
                           class="btn btn-sm btn-primary rounded-pill mt-auto">
                            Register
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

                    <a href="../StudentActivity/Student_Forum/View_question.php?qid=<?php echo $q['question_id']; ?>"
                       onclick="handleNavigation(event, this.href)"
                       class="btn btn-sm btn-dark rounded-pill">
                        View
                    </a>

                </div>

            </div>

        <?php } ?>

    </div>

</div>

<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<script>
function handleNavigation(event, url) {
    event.preventDefault();
    const loader = document.getElementById("loader-wrapper");
    if (loader) loader.classList.remove("d-none");
    setTimeout(() => window.location.href = url, 300);
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>