<?php
//****************if not logged in****************
if (!isset($_SESSION['studentLoggedin']) && !isset($_SESSION['facultyLoggedin'])) {
    header("Location: /DE-Project/_NotLoggedIn.php");
    exit();
}

//****************if faculty logged in****************
if (isset($_SESSION['facultyLoggedin']) && $_SESSION['facultyLoggedin'] == true) {
    header("Location: /DE-Project/Faculty/FacultyCredential/HomePage.php");
    exit();
}
?>

<!-- Latest Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">

    <div class="container">

        <!-- LEFT: Brand -->
        <a class="navbar-brand fw-bold me-4" href="/DE-Project/Student/StudentCredential/HomePage.php">LinkAdemy</a>

        <!-- Toggle -->
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar content -->
        <div class="collapse navbar-collapse" id="navbarNav">

            <!-- LEFT MENU -->
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                <li class="nav-item mx-5">
                    <a class="nav-link active px-3"
                        href="/DE-Project/Student/StudentCredential/HomePage.php">
                        Dashboard
                    </a>
                </li>

                <li class="nav-item mx-5">
                    <a class="nav-link active px-3"
                        href="/DE-Project/Student/StudentActivity/Student_Forum/StudentForum.php">
                        Forum
                    </a>
                </li>

                <li class="nav-item mx-5">
                    <a class="nav-link active px-3"
                        href="/DE-Project/Student/StudentActivity/StudentEvent/showEvent.php">
                        Events
                    </a>
                </li>

            </ul>

            <!-- RIGHT SIDE (Search + Logout grouped) -->
            <div class="d-flex align-items-center gap-2">

                <!-- Search -->
                <form class="d-flex"
                    action="/DE-Project/Student/StudentActivity/SearchUser.php"
                    method="GET">

                    <input class="form-control rounded-pill me-2"
                        type="search"
                        name="search"
                        placeholder="Search users..."
                        required>

                    <button class="btn btn-light rounded-pill px-3">
                        Search
                    </button>
                </form>

                <!-- Logout -->
                <a href="/DE-Project/_Logout.php"
                    class="btn btn-danger rounded-pill px-4">
                    Logout
                </a>

            </div>

        </div>

    </div>

</nav>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>