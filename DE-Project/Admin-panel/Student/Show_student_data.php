<?php
session_start();

// 🔐 Check admin login
if (!isset($_SESSION['AdminLoggedin']) || $_SESSION['AdminLoggedin'] != true) {
    header("Location: ../_NotLoggedIn.php");
    exit();
}

include("../../_DBConnect.php");

// Fetch all students
$query = "SELECT * FROM studentdata ORDER BY enrollment ASC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>

<head>
    <title>View Students</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

    <?php include("../_Navbar.php"); ?>

    <div class="container-fluid mt-4 px-4">

        <!-- Heading + Add Button -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Student List</h2>
            <a href="Add_student_data.php" class="btn btn-success">+ Add Student</a>
        </div>

        <!-- 🔍 Search Input -->
        <div class="mb-3">
            <input type="text" id="searchInput" class="form-control fs-5"
                placeholder="Search by Enrollment, Name, Email, Branch, Mobile..."
                oninput="searchStudent()">
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover align-middle text-center fs-5">

                <thead class="table-dark">
                    <tr>
                        <th class="py-3">Enrollment</th>
                        <th class="py-3">Name</th>
                        <th class="py-3">Email</th>
                        <th class="py-3">Branch</th>
                        <th class="py-3">Mobile</th>
                        <th class="py-3">Action</th>
                    </tr>
                </thead>

                <tbody id="studentTable">

                    <?php
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                    ?>

                            <tr class="student-row">
                                <td class="py-3"><?php echo $row['enrollment']; ?></td>
                                <td class="py-3"><?php echo $row['name']; ?></td>
                                <td class="py-3"><?php echo $row['email']; ?></td>
                                <td class="py-3"><?php echo $row['branch']; ?></td>
                                <td class="py-3"><?php echo $row['mobile']; ?></td>

                                <td>
                                    <div class="d-flex justify-content-center gap-3">
                                        <a href="Edit_student_data.php?id=<?php echo $row['enrollment']; ?>"
                                            class="btn btn-info btn-md px-4">
                                            Edit
                                        </a>

                                        <a href="Delete_student_data.php?id=<?php echo $row['enrollment']; ?>"
                                            class="btn btn-danger btn-md px-4"
                                            onclick="return confirm('Are you sure to delete?');">
                                            Delete
                                        </a>
                                    </div>
                                </td>
                            </tr>

                    <?php
                        }
                    } else {
                        echo "<tr id='noData'><td colspan='6' class='py-4'>No students found</td></tr>";
                    }
                    ?>

                </tbody>

            </table>
        </div>

        <!-- ❌ No Result Message -->
        <div id="noResult" class="text-center text-danger fs-4 mt-3" style="display:none;">
            No matching students found
        </div>

    </div>

    <script>
        function searchStudent() {
            let input = document.getElementById("searchInput").value.trim().toLowerCase();
            let rows = document.querySelectorAll(".student-row");
            let found = false;

            rows.forEach(function(row) {

                let cells = row.querySelectorAll("td");
                let text = "";

                cells.forEach(function(cell) {
                    text += cell.textContent.toLowerCase() + " ";
                });

                if (text.includes(input)) {
                    row.style.display = "";
                    found = true;
                } else {
                    row.style.display = "none";
                }
            });

            // Show/Hide message
            document.getElementById("noResult").style.display = found ? "none" : "block";
        }
    </script>

</body>

</html>