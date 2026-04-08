<?php
include("../../_DBConnect.php");
session_start();

// 🔐 Check admin login
if (!isset($_SESSION['AdminLoggedin']) || $_SESSION['AdminLoggedin'] != true) {
    header("Location: ../_NotLoggedIn.php");
    exit();
}

// 🔁 Handle form submit
if (isset($_POST['addFaculty'])) {

    $email = $_POST['email'];
    $name = $_POST['name'];
    $post = $_POST['post'];
    $branch = $_POST['branch'];
    $mobile = $_POST['mobile'];

    // 🔒 Validation
    if (!preg_match('/^[0-9]{10}$/', $mobile)) {
        echo "<script>alert('Mobile must be 10 digits');</script>";
    }
    else {

        // 🔍 Check duplicate
        $checkQuery = "SELECT * FROM facultydata where email='$email' OR mobile='$mobile'";

        $result = mysqli_query($conn, $checkQuery);

        if (mysqli_num_rows($result) > 0) {

            $row = mysqli_fetch_assoc($result);

            if ($row['email'] == $email) {
                echo "<script>alert('Email already exists');</script>";
            } 
            elseif ($row['mobile'] == $mobile) {
                echo "<script>alert('Mobile already exists');</script>";
            }

        } 
        else {

            // ✅ Insert
            $insertQuery = "INSERT INTO facultydata (email, name, post, branch, mobile) VALUES ('$email', '$name', '$post', '$branch', '$mobile')";

            mysqli_query($conn, $insertQuery);
            header("Location: Show_faculty_data.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Add Faculty</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<?php include("../_Navbar.php"); ?>

<div class="container mt-5">

    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card shadow">

                <div class="card-header bg-dark text-white text-center">
                    <h4>Add Faculty</h4>
                </div>

                <div class="card-body">

                    <form method="POST">
                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        
                        <div class="mb-3">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Post</label>
                            <input type="text" name="post" class="form-control" required>
                        </div>



                        <div class="mb-3">
                            <label>Branch</label>
                            <select name="branch" class="form-control" required>
                                <option value="">Select Branch</option>
                                <option value="CE">CE</option>
                                <option value="IT">IT</option>
                                <option value="EC">EC</option>
                                <option value="ICT">ICT</option>
                                <option value="ME">ME</option>
                                <option value="EE">EE</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Mobile</label>
                            <input type="text" name="mobile" class="form-control"
                                required pattern="[0-9]{10}" maxlength="10"
                                oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,10)">
                        </div>

                        <button type="submit" name="addFaculty" class="btn btn-secondary w-100 mb-2">
                            Add Faculty
                        </button>

                        <a href="./Show_faculty_data.php" class="btn btn-light w-100">Back</a>


                    </form>

                </div>

            </div>

        </div>
    </div>

</div>

</body>
</html>