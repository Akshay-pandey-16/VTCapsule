<?php
session_start();
include 'connect.php'; // Database connection file

// Ensure the user is logged in
if (!isset($_SESSION['email'])) {
    die("Access denied. Please log in first.");
}

$receiver_email = $_SESSION['email'];

// Fetch received capsules
$stmt = $conn->prepare("SELECT m.message_id, m.sender_email, m.title, m.open_date, m.send_date, u.firstName, u.lastName 
                        FROM messages m 
                        JOIN users u ON m.sender_email = u.email
                        WHERE m.receiver_email = ? ORDER BY m.open_date ASC");
$stmt->bind_param("s", $receiver_email);
$stmt->execute();
$result = $stmt->get_result();
$capsules = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Received Capsules</title>
    <link rel="stylesheet" href="received_capsule.css">
    <script>
        function countdown(targetTime, elementId, buttonId) {
            const targetDate = new Date(targetTime).getTime();
            const interval = setInterval(() => {
                const now = new Date().getTime();
                const distance = targetDate - now;

                if (distance < 0) {
                    clearInterval(interval);
                    document.getElementById(elementId).innerHTML = "Ready to open!";
                    document.getElementById(buttonId).style.display = "inline-block"; // Show the Open button
                } else {
                    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                    document.getElementById(elementId).innerHTML = `${hours}h ${minutes}m ${seconds}s`;
                }
            }, 1000);
        }
    </script>
</head>
<body>
    <div class="container">
        <div class="back-arrow">
            <a href="homepage.php" title="Go Back">&larr; Back</a>
        </div>
        <h2>Your Received Capsules</h2>

        <!-- Filter Options -->
        <div class="filters">
            <label for="filter">Filter by:</label>
            <select id="filter" onchange="filterCapsules()">
                <option value="all">All</option>
                <option value="time_remaining">Time Remaining</option>
                <option value="send_date">Send Date</option>
            </select>
        </div>

        <!-- Capsule List -->
        <div id="capsule-list">
            <?php foreach ($capsules as $index => $capsule): ?>
                <div class="capsule" id="capsule-<?php echo $capsule['message_id']; ?>">
                    <p><strong>Sender:</strong> <?php echo htmlspecialchars($capsule['firstName'] . ' ' . $capsule['lastName']); ?></p>
                    <p><strong>Title:</strong> <?php echo htmlspecialchars($capsule['title']); ?></p>
                    <p><strong>Send Date:</strong> <?php echo htmlspecialchars($capsule['send_date']); ?></p>
                    <p><strong>Open Date:</strong> <?php echo htmlspecialchars($capsule['open_date']); ?></p>

                    <?php $current_time = date('Y-m-d H:i:s'); ?>
                    <?php if ($current_time >= $capsule['open_date']): ?>
                        <a href="view_message.php?id=<?php echo $capsule['message_id']; ?>" id="open-btn-<?php echo $index; ?>" class="open-btn">Open</a>
                    <?php else: ?>
                        <p><strong>Time Remaining:</strong> <span id="countdown-<?php echo $index; ?>"></span></p>
                        <a href="view_message.php?id=<?php echo $capsule['message_id']; ?>" id="open-btn-<?php echo $index; ?>" class="open-btn" style="display:none;">Open</a>
                        <script>
                            countdown("<?php echo $capsule['open_date']; ?>", "countdown-<?php echo $index; ?>", "open-btn-<?php echo $index; ?>");
                        </script>
                    <?php endif; ?>

                    <button class="delete-btn" onclick="deleteCapsule(<?php echo $capsule['message_id']; ?>)">Delete</button>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        function deleteCapsule(capsuleId) {
            if (confirm("Are you sure you want to delete this capsule?")) {
                // AJAX request to delete the capsule
                fetch(`delete_capsule.php?id=${capsuleId}`, { method: 'GET' })
                    .then(response => response.text())
                    .then(data => {
                        alert(data);
                        document.getElementById(`capsule-${capsuleId}`).remove();
                    })
                    .catch(error => console.error('Error:', error));
            }
        }

        function filterCapsules() {
            const filter = document.getElementById('filter').value;
            const capsules = document.querySelectorAll('.capsule');
            capsules.forEach(capsule => {
                if (filter === 'all') {
                    capsule.style.display = 'block';
                } else if (filter === 'time_remaining') {
                    // Example logic to filter by remaining time
                    const countdownElement = capsule.querySelector('[id^="countdown-"]');
                    if (countdownElement && countdownElement.textContent !== 'Ready to open!') {
                        capsule.style.display = 'block';
                    } else {
                        capsule.style.display = 'none';
                    }
                } else if (filter === 'send_date') {
                    // Example logic to filter by send date
                    capsule.style.display = 'block'; // Add logic based on your requirements
                }
            });
        }
    </script>
</body>
</html>
