<?php
    session_start();
    include("../../_DBConnect.php");

    //************get student(enrollment) | faculty(email) & it's type(student | faculty) from SearchUser.php************
    $id = $_GET['id'];
    $type = $_GET['type'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-body-tertiary">

<?php include("../loader.php"); ?>
<?php include("../_Navbar.php"); ?>

<div class="container py-5">

    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">

            <?php
            $bg = ($type == "student") ? "bg-primary" : "bg-success";
            $btn = ($type == "student") ? "btn-primary" : "btn-success";

            if ($type == "student") {
                $sql = "SELECT * FROM studentdata WHERE enrollment='$id'";
            } else {
                $sql = "SELECT * FROM facultydata WHERE email='$id'";
            }

            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);
            ?>

            <div class="card shadow border-0 rounded-4 overflow-hidden">

                <!-- Banner -->
                <div class="<?php echo $bg; ?>" style="height: 100px;"></div>

                <!-- Profile Content -->
                <div class="text-center px-4 pb-4">

                    <!-- Avatar -->
                    <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png"
                         class="rounded-circle shadow"
                         width="100"
                         style="margin-top: -50px;">

                    <!-- Name -->
                    <h4 class="fw-bold mt-3 mb-1">
                        <?php echo htmlspecialchars($row['name']); ?>
                    </h4>

                    <!-- Role -->
                    <span class="badge <?php echo $bg; ?>">
                        <?php echo ucfirst($type); ?>
                    </span>

                </div>

                <!-- Info Section -->
                <div class="px-4 pb-4">

                    <div class="list-group list-group-flush">

                        <?php if ($type == "student") { ?>
                            <div class="list-group-item d-flex justify-content-between">
                                <span><i class="bi bi-person-badge me-2"></i>Enrollment</span>
                                <span class="fw-semibold"><?php echo $row['enrollment']; ?></span>
                            </div>
                        <?php } ?>

                        <div class="list-group-item d-flex justify-content-between">
                            <span><i class="bi bi-envelope me-2"></i>Email</span>
                            <span class="fw-semibold"><?php echo $row['email']; ?></span>
                        </div>

                        <?php if ($type == "faculty") { ?>
                            <div class="list-group-item d-flex justify-content-between">
                                <span><i class="bi bi-briefcase me-2"></i>Post</span>
                                <span class="fw-semibold"><?php echo $row['post']; ?></span>
                            </div>
                        <?php } ?>

                        <div class="list-group-item d-flex justify-content-between">
                            <span><i class="bi bi-diagram-3 me-2"></i>Branch</span>
                            <span class="fw-semibold"><?php echo $row['branch']; ?></span>
                        </div>

                        <div class="list-group-item d-flex justify-content-between">
                            <span><i class="bi bi-phone me-2"></i>Mobile</span>
                            <span class="fw-semibold"><?php echo $row['mobile']; ?></span>
                        </div>

                    </div>

                </div>

                <!-- Action -->
                <div class="text-center pb-4">

                    <?php
                    $currentUser = $_SESSION['enrollment'] ?? $_SESSION['email'];

                    if ($currentUser != $id) {
                    ?>
                        <a href="/DE-PROJECT/Student/Student_Message/chat.php?receiver_id=<?php echo $id; ?>&type=<?php echo $type; ?>"
                           class="btn <?php echo $btn; ?> rounded-pill px-4">
                            <i class="bi bi-chat-dots me-1"></i> Message
                        </a>
                    <?php } ?>

                    <a href="javascript:history.back()"
                       class="btn btn-outline-dark rounded-pill px-4 ms-2">
                        <i class="bi bi-arrow-left me-1"></i> Back
                    </a>

                </div>

            </div>

        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>