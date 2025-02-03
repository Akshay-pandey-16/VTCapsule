<?php
session_start();
include 'connect.php'; // Database connection file

// Ensure the user is logged in
if (!isset($_SESSION['email'])) {
    die("Access denied. Please log in first.");
}

if (!isset($_GET['id'])) {
    die("Invalid request. Capsule ID is required.");
}

$message_id = $_GET['id'];
$receiver_email = $_SESSION['email'];

// Fetch message details
$stmt = $conn->prepare("SELECT m.message_id, m.sender_email, m.title, m.message, m.open_date, m.send_date, 
                        u.firstName, u.lastName FROM messages m 
                        JOIN users u ON m.sender_email = u.email
                        WHERE m.message_id = ? AND m.receiver_email = ?");
$stmt->bind_param("is", $message_id, $receiver_email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("No message found or you do not have permission to view this capsule.");
}

$message = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Message</title>
    <link rel="stylesheet" href="view_message.css">
</head>
<body>
    <div class="container">
        <!-- Back Arrow -->
        <div class="back-arrow">
            <a href="received_capsule.php" title="Go Back">&larr; Back</a>
        </div>

        <h2>View Capsule</h2>
        <div class="message-details">
            <p><strong>Sender Name:</strong> <?php echo htmlspecialchars($message['firstName'] . ' ' . $message['lastName']); ?></p>
            <p><strong>Sender Email:</strong> <?php echo htmlspecialchars($message['sender_email']); ?></p>
            <p><strong>Sent Date & Time:</strong> <?php echo htmlspecialchars($message['send_date']); ?></p>
            <p><strong>Open Date & Time:</strong> <?php echo htmlspecialchars($message['open_date']); ?></p>
            <p><strong>Title:</strong> <?php echo htmlspecialchars($message['title']); ?></p>
            <p><strong>Message:</strong></p>
            <div class="message-box">
                <?php echo nl2br(htmlspecialchars($message['message'])); ?>
            </div>
        </div>
        <div class="actions">
            <button id="replyButton">Reply</button>
            <button id="deleteButton">Delete</button>
            <button id="moreButton">More</button>
        </div>
    </div>

    <script src="view_message.js"></script>
</body>
</html>
