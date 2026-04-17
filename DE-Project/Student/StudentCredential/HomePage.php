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

<body class="bg-light">

    <?php include("../loader.php"); ?> <!-- FIRST -->
    <?php include("../_Navbar.php"); ?>

    <div class="container py-5">

        <div class="row justify-content-center mb-5">

            <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5">

                <div class="card shadow-lg border-0 rounded-4">

                    <div class="card-header bg-primary text-white text-center rounded-top-4">
                        <h4 class="mb-0 fw-semibold">Student Profile</h4>
                    </div>

                    <div class="card-body text-center p-5">

                        <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" class="rounded-circle img-fluid w-50 mb-3">

                        <h4 class="fw-bold"><?php echo $row['name']; ?></h4>
                        <hr>

                        <div class="text-start">

                            <!--===============display data from $row[array]===============-->
                            <p><b>Enrollment:</b> <?php echo $row['enrollment']; ?></p>
                            <p><b>Email:</b> <?php echo $row['email']; ?></p>
                            <p><b>Branch:</b> <?php echo $row['branch']; ?></p>
                            <p><b>Mobile:</b> <?php echo $row['mobile']; ?></p>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!--===============TOP 5 EVENTS===============-->
        <div class="mb-5">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="fw-bold">Latest Events</h4>
            </div>
            <hr>

            <div class="row g-4">

                <!--===============fetch row data from table in an array formate===============-->
                <?php while ($event = mysqli_fetch_assoc($eventResult)) { ?>

                    <div class="col-12 col-md-6 col-lg-4">

                        <div class="card shadow-sm rounded-4 h-100 p-3">

                            <h6 class="fw-bold mb-1">
                                <?php echo $event['event_name']; ?>
                            </h6>

                            <small class="text-muted mb-2 d-block">
                                <?php echo date("d M Y, h:i A", strtotime($event['event_datetime'])); ?>
                            </small>

                            <p class="mb-1">
                                <b>Place:</b> <?php echo $event['event_place']; ?>
                            </p>

                            <a href="<?php echo $event['event_form_link']; ?>" target="_blank" class="btn btn-sm btn-primary mt-2 rounded-pill">Register </a>

                        </div>

                    </div>

                <?php } ?>

            </div>

        </div>


        <!--===============TOP 2 QUESTIONS===============-->
        <div>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="fw-bold">Top Questions</h4>
            </div>
            <hr>

            <!--===============fetch row data from table in an array formate===============-->
            <?php while ($q = mysqli_fetch_assoc($questionResult)) { ?>

                <div class="card shadow-sm mb-3 p-3 rounded-4">

                    <p class="mb-2 fw-semibold">
                        <?php echo $q['question']; ?>
                    </p>

                    <div class="d-flex justify-content-between align-items-center">

                        <span class="badge bg-success">
                            Answers: <?php echo $q['answer_count']; ?>
                        </span>

                        <a href="../StudentActivity/Student_Forum/View_question.php?qid=<?php echo $q['question_id']; ?>"
                            onclick="handleNavigation(event, this.href)"
                            class="btn btn-sm btn-dark rounded-pill">View</a>
                    </div>

                </div>

            <?php } ?>

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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>