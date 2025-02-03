// Add event listeners for the buttons
document.getElementById("replyButton").addEventListener("click", function () {
    const senderEmail = "<?php echo $message['sender_email']; ?>";
    window.location.href = `reply_message.php?to=${encodeURIComponent(senderEmail)}`;
});

document.getElementById("deleteButton").addEventListener("click", function () {
    const confirmation = confirm("Are you sure you want to delete this capsule?");
    if (confirmation) {
        const messageId = "<?php echo $message['message_id']; ?>";
        window.location.href = `delete_message.php?id=${messageId}`;
    }
});

document.getElementById("moreButton").addEventListener("click", function () {
    alert("More options coming soon!");
});
