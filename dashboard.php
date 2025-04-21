<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.html");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - ResumePro</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        .navbar {
            background-color: #2c2c2c;
            overflow: hidden;
            padding: 12px 20px;
        }

        .navbar a {
            float: right;
            color: white;
            text-decoration: none;
            padding: 12px;
            font-size: 16px;
        }

        .navbar a:hover {
            background-color: #555;
        }

        .navbar .logo {
            float: left;
            font-weight: bold;
            font-size: 22px;
        }

        .navbar .logo a {
            color: white;
            text-decoration: none;
        }

        .navbar .logo a:focus,
        .navbar .logo a:active {
            outline: none;
            color: white;
        }

        .dashboard-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 80vh;
            text-align: center;
        }

        .dashboard-container h2 {
            margin-bottom: 20px;
        }

        .dashboard-container a {
            display: inline-block;
            margin: 10px;
            padding: 12px 24px;
            background-color: #2c2c2c;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .dashboard-container a:hover {
            background-color: #555;
        }

        .footer {
            background-color: #2c2c2c;
            color: white;
            text-align: center;
            padding: 10px;
            position: fixed;
            bottom: 0;
            width: 100%;
        }

    </style>
</head>
<body>

    <div class="navbar">
        <div class="logo"><a href="index.html">ResumePro</a></div>
        <a href="logout.php">Logout</a>
        <a href="dashboard.php">Dashboard</a>
        <a href="about.html">About</a>
        <a href="upload.html">Analyze Resume</a>
    </div>

    <div class="dashboard-container">
        <h2>Welcome to Your Dashboard</h2>
        <a href="upload.html">Analyze New Resume</a>
        <a href="history.html">View Past Results</a>
    </div>

    <div class="footer">
        &copy; 2024 ResumePro. All rights reserved.
    </div>

</body>
</html>
