<?php
session_start();

    //****************if faculty logged in****************
    if (isset($_SESSION['AdminLoggedin']) && $_SESSION['AdminLoggedin'] == true) {
        header("Location: ./Admin-panel/Admin_Credential/HomePage.php");
        exit();
    }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Not Logged In</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css">
</head>

<body>

<div class="container">
    <div class="jumbotron my-5">
        <div class="text-center">

            <h1 class="display-4 text-danger">Error...!</h1>
            <p class="lead">Sorry, You are not Signed In.</p>
            <hr>

            <p>Click below to Sign In</p>
            <a class="btn btn-outline-danger btn-lg" href="./Admin_Credential/index.php">Sign In</a>

        </div>
    </div>
</div>

</body>
</html>