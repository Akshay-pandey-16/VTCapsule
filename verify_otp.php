<!-- file name - verify_otp.php -->
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (!empty($data['otp'])) {
    $enteredOtp = $data['otp'];
    
    if (isset($_SESSION['otp']) && $_SESSION['otp'] == $enteredOtp) {
        $_SESSION['otp_verified'] = true;
        $_SESSION['otp_email_verified'] = $_SESSION['otp_email']; 
        echo json_encode(['success' => true, 'message' => 'OTP verified successfully!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid OTP.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'OTP is missing.']);
}

?>
