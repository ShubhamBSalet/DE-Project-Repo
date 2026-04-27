<?php
session_start();
include("../../_DBConnect.php");
include("encryption.php");

if (!isset($_SESSION['facultyLoggedin']) || $_SESSION['facultyLoggedin'] != true) {
    header("Location: ../../_NotLoggedIn.php");
    exit();
}

// STEP 1: Detect sender
if (isset($_SESSION['email'])) {
    $sender = $_SESSION['email'];
    $sender_type = "faculty";
} elseif (isset($_SESSION['enrollment'])) {
    $sender = $_SESSION['enrollment'];
    $sender_type = "student";
} else {
    die("Login required");
}

// STEP 2: Get receiver
if (!isset($_GET['receiver_id']) || !isset($_GET['type'])) {
    die("Invalid request");
}

$receiver = $_GET['receiver_id'];
$receiver_type = $_GET['type'];

// STEP 3: Prevent self chat
if ($sender == $receiver && $sender_type == $receiver_type) {
    die("You cannot message yourself");
}

// STEP 4: Send message
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
    <title>Faculty Chat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-body-tertiary">

<?php include("../_Navbar.php"); ?>

<div class="container py-4">

    <div class="row justify-content-center">

        <div class="col-lg-6 col-md-8">

            <div class="card shadow border-0 rounded-4 overflow-hidden">

                <!-- CHAT HEADER -->
                <div class="bg-success text-white px-4 py-3 d-flex align-items-center justify-content-between">
                    <h6 class="mb-0 fw-semibold">
                        💬 Chat
                    </h6>
                </div>

                <!-- CHAT BODY -->
                <div id="chatBox"
                     class="p-3"
                     style="height:400px; overflow-y:auto; background:#f8f9fa;">

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
                            <div class='d-flex justify-content-end mb-3'>
                                <div class='bg-success text-white px-3 py-2 rounded-4 shadow-sm' style='max-width:70%;'>
                                    $msg
                                </div>
                            </div>";
                        } else {
                            echo "
                            <div class='d-flex justify-content-start mb-3'>
                                <div class='bg-white border px-3 py-2 rounded-4 shadow-sm' style='max-width:70%;'>
                                    $msg
                                </div>
                            </div>";
                        }
                    }
                    ?>

                </div>

                <!-- INPUT AREA -->
                <div class="border-top p-3 bg-white">

                    <form method="POST">

                        <div class="input-group">

                            <input type="text"
                                   name="message"
                                   class="form-control rounded-pill"
                                   placeholder="Type a message..."
                                   required>

                            <button type="submit"
                                    name="send"
                                    class="btn btn-success rounded-pill ms-2 px-4">
                                Send
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

<script>
    const chatBox = document.getElementById('chatBox');
    chatBox.scrollTop = chatBox.scrollHeight;
</script>

</body>

<script>
    const chatBox = document.querySelector('.border');
    chatBox.scrollTop = chatBox.scrollHeight;
</script>

</html>