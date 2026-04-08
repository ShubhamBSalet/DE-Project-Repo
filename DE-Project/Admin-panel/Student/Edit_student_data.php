<?php
session_start();

if (!isset($_SESSION['AdminLoggedin']) || $_SESSION['AdminLoggedin'] != true) {
    header("Location: ../_NotLoggedIn.php");
    exit();
}

include("../../_DBConnect.php");

$id = $_GET['id'];

// Fetch existing data
$query = "SELECT * FROM studentdata WHERE enrollment='$id'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

// Update logic
if (isset($_POST['update'])) {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $branch = $_POST['branch'];
    $mobile = $_POST['mobile'];

    $updateQuery = "UPDATE studentdata SET 
        name='$name',
        email='$email',
        branch='$branch',
        mobile='$mobile'
        WHERE enrollment='$id'";

    mysqli_query($conn, $updateQuery);

    header("Location: Show_student_data.php");
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <?php include("../_Navbar.php"); ?>

    <div class="container mt-5">

        <div class="row justify-content-center">
            <div class="col-md-6">

                <div class="card shadow">

                    <div class="card-header bg-dark text-white text-center">
                        <h4>Edit Student</h4>
                    </div>

                    <div class="card-body">

                        <form method="POST">

                            <div class="mb-3">
                                <label>Enrollment</label>
                                <input type="text" class="form-control"
                                    value="<?php echo $row['enrollment']; ?>" disabled>
                            </div>

                            <div class="mb-3">
                                <label>Name</label>
                                <input type="text" name="name" class="form-control"
                                    value="<?php echo $row['name']; ?>" required>
                            </div>

                            <div class="mb-3">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control"
                                    value="<?php echo $row['email']; ?>" required>
                            </div>

                            <div class="mb-3">
                                <label>Branch</label>
                                <select name="branch" class="form-control" required>
                                    <option value="CE" <?php if ($row['branch'] == "CE") echo "selected"; ?>>CE</option>
                                    <option value="IT" <?php if ($row['branch'] == "IT") echo "selected"; ?>>IT</option>
                                    <option value="EC" <?php if ($row['branch'] == "EC") echo "selected"; ?>>EC</option>
                                    <option value="ICT" <?php if ($row['branch'] == "ICT") echo "selected"; ?>>ICT</option>
                                    <option value="ME" <?php if ($row['branch'] == "ME") echo "selected"; ?>>ME</option>
                                    <option value="EE" <?php if ($row['branch'] == "EE") echo "selected"; ?>>EE</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label>Mobile</label>
                                <input type="text" name="mobile" class="form-control"
                                    value="<?php echo $row['mobile']; ?>" required>
                            </div>

                            <button type="submit" name="update" class="btn btn-secondary w-100 mb-2">
                                Edit
                            </button>

                            <a href="Show_student_data.php" class="btn btn-light w-100">Back</a>

                        </form>

                    </div>

                </div>

            </div>
        </div>

    </div>

</body>

</html>