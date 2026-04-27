<?php
    session_start();
    include("../../_DBConnect.php");
    include("encryption.php");

    //****************if student not logged in****************
    if (!isset($_SESSION['studentLoggedin'])) {
        header("Location: /DE-Project/_NotLoggedIn.php");
        exit();
    }

    //****************Detect sender is student | faculty****************
    if (isset($_SESSION['enrollment'])) {
        $sender = $_SESSION['enrollment'];
        $sender_type = "student";
    } 
    elseif (isset($_SESSION['email'])) {
        $sender = $_SESSION['email'];
        $sender_type = "faculty";
    } 
    else {
        die("Login required");
    }

    //****************if receiver id | type null or empty****************
    if (!isset($_GET['receiver_id']) || !isset($_GET['type'])) {
        die("Invalid request");
    }

    //****************get receiver id & type****************
    $receiver = $_GET['receiver_id'];
    $receiver_type = $_GET['type'];

    //****************Prevent self chat****************
    if ($sender == $receiver && $sender_type == $receiver_type) {
        die("You cannot message yourself");
    }

    //****************Send message****************
    if (isset($_POST['send'])) {
        $message = $_POST['message'];
        $encryptedMsg = encryptMessage($message);

        $sql = "INSERT INTO messages (sender_id, sender_type, receiver_id, receiver_type, message) VALUES ('$sender', '$sender_type', '$receiver', '$receiver_type', '$encryptedMsg')";

        mysqli_query($conn, $sql);
    }

    
?>

<!DOCTYPE html>
<html>

<head>
    <title>Chat</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-body-tertiary">

<?php include("../_Navbar.php"); ?>

<div class="container py-4">

    <!-- ================= CHAT CARD ================= -->
    <div class="card shadow border-0 rounded-4 overflow-hidden">

        <!-- HEADER -->
        <div class="bg-primary text-white p-3 d-flex align-items-center gap-3">

            <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png"
                 width="40"
                 class="rounded-circle">

            <div>
                <h6 class="mb-0 fw-semibold">Chat</h6>
                <small class="opacity-75"><?php echo ucfirst($receiver_type); ?></small>
            </div>

        </div>

        <!-- CHAT BODY -->
        <div id="chatBox"
             class="p-3"
             style="height: 400px; overflow-y: auto; background: #f8f9fa;">

            <?php
            $sql = "SELECT * FROM messages 
                WHERE (sender_id='$sender' AND sender_type='$sender_type' 
                AND receiver_id='$receiver' AND receiver_type='$receiver_type')
                OR (sender_id='$receiver' AND sender_type='$receiver_type' 
                AND receiver_id='$sender' AND receiver_type='$sender_type')
                ORDER BY created_at ASC";

            $result = mysqli_query($conn, $sql);

            while ($row = mysqli_fetch_assoc($result)) {

                $msg = decryptMessage($row['message']);

                if ($row['sender_id'] == $sender) {
            ?>

                    <!-- SENT MESSAGE -->
                    <div class="d-flex justify-content-end mb-2">
                        <div class="bg-primary text-white px-3 py-2 rounded-4 shadow-sm"
                             style="max-width: 70%;">
                            <?php echo $msg; ?>
                        </div>
                    </div>

                <?php } else { ?>

                    <!-- RECEIVED MESSAGE -->
                    <div class="d-flex justify-content-start mb-2">
                        <div class="bg-white text-dark px-3 py-2 rounded-4 shadow-sm border"
                             style="max-width: 70%;">
                            <?php echo $msg; ?>
                        </div>
                    </div>

                <?php } ?>

            <?php } ?>

        </div>

        <!-- INPUT -->
        <div class="p-3 border-top bg-white">

            <form method="POST">

                <div class="input-group">

                    <input type="text"
                           name="message"
                           class="form-control rounded-pill"
                           placeholder="Type a message..."
                           required>

                    <button type="submit"
                            name="send"
                            class="btn btn-primary rounded-pill ms-2 px-4">
                        <i class="bi bi-send"></i>
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<script>
const chatBox = document.getElementById("chatBox");
chatBox.scrollTop = chatBox.scrollHeight;
</script>

</body>

</html>