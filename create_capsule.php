<!-- file name - create_capsule.php -->
<?php
session_start();
include 'connect.php'; // Include DB connection file

// Ensure the user is logged in
if (!isset($_SESSION['email'])) {
    die("Access denied. Please log in first.");
}

$sender_email = $_SESSION['email'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate input fields
    if (!isset($_POST['receiver_email'], $_POST['title'], $_POST['message'], $_POST['open_date'])) {
        die("Error: Missing required fields.");
    }

    $receiver_email = trim($_POST['receiver_email']);
    $title =  trim($_POST['title']);
    $message = trim($_POST['message']);
    $open_date = trim($_POST['open_date']);

    // Validate date format (Optional but recommended)
    if (!strtotime($open_date)) {
        die("Error: Invalid date format.");
    }

    // Fetch sender_id using sender's email
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $sender_email);
    $stmt->execute();
    $stmt->bind_result($sender_id);
    $stmt->fetch();
    $stmt->close();

    // Fetch receiver_id using receiver's email
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $receiver_email);
    $stmt->execute();
    $stmt->bind_result($receiver_id);
    $stmt->fetch();
    $stmt->close();

    // Ensure the recipient exists
    if (!$receiver_id) {
        die("Error: The recipient email is not registered.");
    }

    // Insert message into the database
    $stmt = $conn->prepare("INSERT INTO messages (sender_id, sender_email, receiver_id, receiver_email, title, message, open_date) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssss", $sender_id, $sender_email, $receiver_id, $receiver_email, $title, $message, $open_date);

    if ($stmt->execute()) {
        echo "Message scheduled successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
