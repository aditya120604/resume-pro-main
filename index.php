<?php
session_start();
if (!isset($_SESSION['email'])) {
    // Redirect to login page if user is not logged in
    header("Location: login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard - ResumePro</title>
  <link rel="stylesheet" href="styles.css">
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background-color: #f7f7f7;
    }
    nav {
      background-color: #2b2b2b;
      padding: 15px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    nav a {
      color: white;
      margin-left: 20px;
      text-decoration: none;
    }
    nav a:hover {
      text-decoration: underline;
    }
    .logo {
      font-size: 24px;
      font-weight: bold;
      color: white;
      text-decoration: none;
    }
    .dashboard-container {
      max-width: 600px;
      margin: 60px auto;
      padding: 30px;
      background-color: #fff;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      text-align: center;
      border-radius: 8px;
    }
    .dashboard-container h2 {
      margin-bottom: 20px;
    }
    .dashboard-container a.button {
      display: inline-block;
      margin: 10px;
      padding: 12px 25px;
      background-color: #2b2b2b;
      color: white;
      text-decoration: none;
      border-radius: 6px;
      transition: background-color 0.3s ease;
    }
    .dashboard-container a.button:hover {
      background-color: #444;
    }
  </style>
</head>
<body>
  <nav>
    <a class="logo" href="index.html">ResumePro</a>
    <div>
      <a href="about.html">About</a>
      <a href="login.html">Login</a>
      <a href="signup.html">Sign Up</a>
      <a href="logout.php">Logout</a>
    </div>
  </nav>

  <div class="dashboard-container">
    <h2>Welcome to Your Dashboard</h2>
    <p>What would you like to do?</p>
    <a href="upload.html" class="button">Analyze Resume</a>
    <a href="previous_results.html" class="button">View Previous Results</a>
    <a href="logout.php" class="button">Logout</a>
  </div>
</body>
</html>
