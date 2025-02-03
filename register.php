<!--file name = register.php -->
<?php
include 'connect.php';
if (!isset($_SESSION)) {
    session_start();
}

// Handle Sign-Up
if (isset($_POST['signUp'])) {
    $firstName = $conn->real_escape_string($_POST['fName']);
    $lastName = $conn->real_escape_string($_POST['lName']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $checkEmail = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($checkEmail);

    if ($result->num_rows > 0) {
        $_SESSION['error'] = "Email Address Already Exists!";
        header("Location: index.php");
        exit();
    } else {
        if (isset($_SESSION['otp_verified']) && $_SESSION['otp_verified'] === true && $_SESSION['otp_email'] === $email) {
            $insertQuery = "INSERT INTO users (firstName, lastName, email, password) VALUES ('$firstName', '$lastName', '$email', '$password')";
            if ($conn->query($insertQuery) === TRUE) {
                unset($_SESSION['otp_verified'], $_SESSION['otp_email']);
                $_SESSION['success'] = "Registration Successful!";
                header("Location: index.php");
                exit();
            } else {
                $_SESSION['error'] = "Error: " . $conn->error;
                header("Location: index.php");
                exit();
            }
        } else {
            $_SESSION['error'] = "OTP verification failed. Please verify your OTP.";
            header("Location: index.php");
            exit();
        }
    }
}

// Handle Sign-In

if (isset($_POST['signIn'])) {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result) {
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            if (password_verify($password, $row['password'])) {
                $_SESSION['email'] = $row['email'];
                header("Location: homepage.php");
                exit();
            } else {
                echo "Invalid Email or Password.";
            }
        } else {
            echo "Invalid Email or Password.";
        }
    } else {
        echo "Database Query Error: " . $conn->error;
    }
}
?>

