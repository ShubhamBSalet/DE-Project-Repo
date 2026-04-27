<?php
//****************if faculty not logged in****************
if (!isset($_SESSION['facultyLoggedin']) || $_SESSION['facultyLoggedin'] != true) {
    header("Location: /DE-Project/_NotLoggedIn.php");
    exit();
}

//****************if student logged in****************
if (isset($_SESSION['studentLoggedin']) && $_SESSION['studentLoggedin'] == true) {
    header("Location: /DE-Project/Student/StudentCredential/HomePage.php");
    exit();
}
?>

<!-- Latest Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<nav class="navbar navbar-expand-lg navbar-dark bg-success shadow-sm">

    <div class="container-fluid">

        <!-- Brand -->
        <a class="navbar-brand fw-bold"
           href="/DE-Project/Faculty/FacultyCredential/HomePage.php">
            LinkAdemy
        </a>

        <!-- Toggle -->
        <button class="navbar-toggler border-0"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">

            <!-- LEFT MENU -->
            <ul class="navbar-nav me-auto">

                <li class="nav-item px-4">
                    <a class="nav-link active"
                       href="/DE-Project/Faculty/FacultyCredential/HomePage.php">
                        Dashboard
                    </a>
                </li>

                <li class="nav-item px-4">
                    <a class="nav-link active"
                       href="/DE-Project/Faculty/FacultyActivity/Faculty_Forum/FacultyForum.php">
                        Forum
                    </a>
                </li>

                <li class="nav-item px-4">
                    <a class="nav-link active"
                       href="/DE-Project/Faculty/FacultyActivity/FacultyEvent/showEvent.php">
                        Post Event
                    </a>
                </li>

                <li class="nav-item px-4">
                    <a class="nav-link active"
                       href="/DE-Project/Faculty/FacultyActivity/FacultyAttendance/index.php">
                        Attendance
                    </a>
                </li>

            </ul>

            <!-- RIGHT SIDE -->
            <div class="d-flex align-items-center gap-2">

                <form class="d-flex"
                      action="/DE-Project/Faculty/FacultyActivity/SearchUser.php"
                      method="GET">

                    <input class="form-control me-2"
                           type="search"
                           name="search"
                           placeholder="Search users..."
                           required>

                    <button class="btn btn-light px-3">
                        Search
                    </button>
                </form>

                <a href="/DE-Project/_Logout.php"
                   class="btn btn-danger px-4">
                    Logout
                </a>

            </div>

        </div>

    </div>

</nav>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>