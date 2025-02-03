<!--file name - homepage.php-->
<?php
session_start();
include("connect.php");

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Virtual Time Capsule Dashboard</title>
  <link rel="stylesheet" href="homepage.css">
</head>
<body>

  <div class="container">
    <header>
      <h1>Welcome to Virtual Time Capsule</h1>
      <nav>
        <button class="nav-toggle">Menu</button>
        <ul class="nav-menu">
          <li><a href="#">Profile</a></li>
          <li><a href="received_capsule.php">Received Capsule</a></li>
          <li><a href="create_capsule.html">Create New Capsule</a></li>
          <li><a href="#">Sent Capsule</a></li>
          <li><a href="#">Settings</a></li>
          <li><a href="#">Logout</a></li>
        </ul>
      </nav>
    </header>

    <div class="main-content">
      <section class="welcome">
        <h2>Hello, User!</h2>
        <p>Welcome back to Virtual time capsule. You can send memories, messages, and more.</p>
      </section>

      <section class="capsule-options">
        <div class="option">
          <h3>View Your Capsule</h3>
          <p>Access and review your sent messages and memories.</p>
          <button>View Capsule</button>
        </div>
        <div class="option">
          <h3>Create a New Capsule</h3>
          <p>Create a new capsule to send your messages to your loved ones in future.</p>
          <button><a href="create_capsule.html">Create Capsule</a></button>
        </div>
        <div class="option">
          <h3>Check Received Capsule</h3>
          <p>Check the capsules which you have received from your loved ones.</p>
          <button><a href="received_capsule.php">Check Capsule</a></button>
        </div>
      </section>

      <section class="settings">
        <h3>Account Settings</h3>
        <button>Update Profile</button>
        <button>Change Password</button>
        <button>Delete Capsule</button>
      </section>
    </div>
  </div>
  

  <script>
    // JavaScript for the navigation menu toggle on small screens
    const navToggle = document.querySelector('.nav-toggle');
    const navMenu = document.querySelector('.nav-menu');

    navToggle.addEventListener('click', () => {
      navMenu.classList.toggle('active');
    });
  </script>

</body>
</html>
