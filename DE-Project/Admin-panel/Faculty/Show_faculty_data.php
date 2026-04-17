<?php
session_start();

// 🔐 Check admin login
if (!isset($_SESSION['AdminLoggedin']) || $_SESSION['AdminLoggedin'] != true) {
    header("Location: ../_NotLoggedIn.php");
    exit();
}

include("../../_DBConnect.php");

// Fetch all faculty
$query = "SELECT * FROM facultydata ORDER BY name ASC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>

<head>
    <title>View Faculty</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<?php include("../_Navbar.php"); ?>

<div class="container-fluid mt-4 px-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Faculty List</h2>
            <div class="d-flex gap-2">
                <a href="Add_faculty_data.php" class="btn btn-success">+ Add Faculty</a>
                <a href="Add_faculty_excel.php" class="btn btn-outline-success">+ Add Faculty (.CSV)</a>
            </div>
        </div>

    <!-- 🔍 Search Input -->
    <div class="mb-3">
        <input type="text" id="searchInput" class="form-control fs-5"
            placeholder="Search by Email, Name, Post, Branch, Mobile..."
            oninput="searchFaculty()">
    </div>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover align-middle text-center fs-5">

            <thead class="table-dark">
                <tr>
                    <th class="py-3">Email</th>
                    <th class="py-3">Name</th>
                    <th class="py-3">Post</th>
                    <th class="py-3">Branch</th>
                    <th class="py-3">Mobile</th>
                    <th class="py-3">Action</th>
                </tr>
            </thead>

            <tbody>

                <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                ?>

                <tr class="faculty-row">
                    <td class="py-3"><?php echo $row['email']; ?></td>
                    <td class="py-3"><?php echo $row['name']; ?></td>
                    <td class="py-3"><?php echo $row['post']; ?></td>
                    <td class="py-3"><?php echo $row['branch']; ?></td>
                    <td class="py-3"><?php echo $row['mobile']; ?></td>

                    <td>
                        <div class="d-flex justify-content-center gap-3">
                            <a href="Edit_faculty_data.php?id=<?php echo $row['email']; ?>"
                               class="btn btn-info btn-md px-4">
                               Edit
                            </a>

                            <a href="Delete_faculty_data.php?id=<?php echo $row['email']; ?>"
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
                    echo "<tr><td colspan='6' class='py-4'>No faculties found</td></tr>";
                }
                ?>

            </tbody>

        </table>
    </div>

    <!-- ❌ No Result Message -->
    <div id="noResult" class="text-center text-danger fs-4 mt-3" style="display:none;">
        No matching faculty found
    </div>

</div>

<!-- 🔍 Search Script -->
<script>
function searchFaculty() {
    let input = document.getElementById("searchInput").value.trim().toLowerCase();
    let rows = document.querySelectorAll(".faculty-row");
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

    // Show/Hide No Result Message
    document.getElementById("noResult").style.display = found ? "none" : "block";
}
</script>

</body>
</html>