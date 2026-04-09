<?php
session_start();
include("../../_DBConnect.php");
include("encryption.php");

//****************if student not logged in****************
if (!isset($_SESSION['studentLoggedin'])) {
    header("Location: /DE-Project/_NotLoggedIn.php");
    exit();
}

// STEP 1: Detect sender
if (isset($_SESSION['enrollment'])) {
    $sender = $_SESSION['enrollment'];
    $sender_type = "student";
} elseif (isset($_SESSION['email'])) {
    $sender = $_SESSION['email'];
    $sender_type = "faculty";
} else {
    die("Login required");
}

// STEP 2: Get receiver
if (!isset($_GET['receiver_id']) || !isset($_GET['type'])) {
    die("Invalid request");
}

$receiver = $_GET['receiver_id'];
$receiver_type = $_GET['type'];

// STEP 4: Prevent self chat
if ($sender == $receiver && $sender_type == $receiver_type) {
    die("You cannot message yourself");
}

// STEP 5: Send message
if (isset($_POST['send'])) {
    $message = $_POST['message'];
    $encryptedMsg = encryptMessage($message);

    $sql = "INSERT INTO messages 
(sender_id, sender_type, receiver_id, receiver_type, message) 
VALUES 
('$sender', '$sender_type', '$receiver', '$receiver_type', '$encryptedMsg')";

    mysqli_query($conn, $sql);
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Chat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php include("../_Navbar.php"); ?>

    <div class="container mt-5">

        <h4 class="mb-3">Chat</h4>

        <!-- CHAT BOX -->
        <!-- <div class="border p-3 mb-3" style="height:400px; overflow-y:auto; background-color: black;"> -->

        <div class="border p-3 mb-3 rounded-4 shadow"
            style="height:400px; overflow-y:auto; 
     background: linear-gradient(135deg, #6fbbde, #858fe8); color:white; scroll-behavior:smooth;">
            <?php
            $sql = "SELECT * FROM messages 
WHERE 
(sender_id='$sender' AND sender_type='$sender_type' 
 AND receiver_id='$receiver' AND receiver_type='$receiver_type')

OR

(sender_id='$receiver' AND sender_type='$receiver_type' 
 AND receiver_id='$sender' AND receiver_type='$sender_type')

ORDER BY created_at ASC";

            $result = mysqli_query($conn, $sql);

            while ($row = mysqli_fetch_assoc($result)) {

                $msg = decryptMessage($row['message']);

                if ($row['sender_id'] == $sender) {
                    echo "
    <div class='d-flex justify-content-end mb-2'>
        <div class='bg-primary text-white px-3 py-2 rounded-3 shadow-sm' style='max-width:70%;'>
            $msg
        </div>
    </div>";
                } else {
                    echo "
    <div class='d-flex justify-content-start mb-2'>
        <div class='bg-light text-dark px-3 py-2 rounded-3 shadow-sm' style='max-width:70%;'>
            $msg
        </div>
    </div>";
                }
            }
            ?>

        </div>

        <!-- MESSAGE FORM -->
        <form method="POST">
            <div class="input-group shadow-sm">

                <input type="text"
                    name="message"
                    class="form-control rounded-start-pill"
                    required
                    placeholder="Write message...">

                <button type="submit"
                    name="send"
                    class="btn btn-primary rounded-end-pill px-4">
                    Send
                </button>

            </div>
        </form>

    </div>

</body>
<script>
    const chatBox = document.querySelector('.border');
    chatBox.scrollTop = chatBox.scrollHeight;
</script>

</html>