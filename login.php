<?php
require_once 'config.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    // Validate inputs
    if (empty($email)) {
        $errors[] = "Email is required";
    }
    
    if (empty($password)) {
        $errors[] = "Password is required";
    }
    
    // If no errors, check credentials
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT id, full_name, email, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['full_name'];
                $_SESSION['user_email'] = $user['email'];
                
                // Redirect to dashboard
                header("Location: dashboard.php");
                exit();
            } else {
                $errors[] = "Invalid email or password";
            }
        } else {
            $errors[] = "Invalid email or password";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login and Sign Up - ResumePro</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #333;
            color: white;
            padding: 1rem;
            text-align: center;
        }
        .auth-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .auth-box {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            width: 320px;
            text-align: center;
        }
        h2 {
            color: #333;
        }
        input[type="text"], input[type="email"], input[type="password"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            background-color: #333;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #555;
        }
        .social-buttons {
            margin-top: 15px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .social-buttons button {
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .google-btn { background-color: #db4437; color: white; }
        .facebook-btn { background-color: #4267B2; color: white; }
        .linkedin-btn { background-color: #0077B5; color: white; }
        footer {
            text-align: center;
            padding: 1rem;
            position: fixed;
            bottom: 0;
            width: 100%;
            background-color: #333;
            color: #fff;
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <header class="navbar">
        <div class="container">
            <a href="index.html" style="color: white; text-decoration: none;">
                <h1 style="margin: 0; display: inline;">ResumePro</h1>
            </a>            
            <nav>
                <ul class="nav-links">
                    <li><a href="index.html">Home</a></li>
                    <li><a href="upload.html">Analyze Resume</a></li>
                    <li><a href="login.html">Login</a></li>
                    <li><a href="about.html">About</a></li>
                    <!-- <li><a href="signup.html">Sign Up</a></li> -->
                </ul>
            </nav>
        </div>
    </header>

    <div class="auth-container">
        <div class="auth-box">
            <h2>Login to ResumePro</h2>
            <form action="dashboard.php" method="POST">
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Login</button>
            </form>

            <div class="social-buttons">
                <button class="google-btn" onclick="redirectTo('google')">Sign in with Google</button>
                <button class="facebook-btn" onclick="redirectTo('facebook')">Sign in with Facebook</button>
                <button class="linkedin-btn" onclick="redirectTo('linkedin')">Sign in with LinkedIn</button>
            </div>
            <div class="link-section">
                <p>New to ResumePro <a href="signup.html">Sign up here</a>.</p>
            </div>
        </div>
        
    </div>

    <footer>
        © 2024 ResumePro. All rights reserved.
    </footer>

    <script>
        function redirectTo(provider) {
            let redirectUrls = {
                google: 'https://accounts.google.com/o/oauth2/v2/auth',
                facebook: 'https://www.facebook.com/v13.0/dialog/oauth',
                linkedin: 'https://www.linkedin.com/oauth/v2/authorization'
            };

            const clientId = {
                google: 'YOUR_GOOGLE_CLIENT_ID',
                facebook: 'YOUR_FACEBOOK_APP_ID',
                linkedin: 'YOUR_LINKEDIN_CLIENT_ID'
            };

            let redirectUri = 'http://localhost:3000/auth/callback'; // Update this to your app's callback URL.

            const authUrl = `${redirectUrls[provider]}?response_type=code&client_id=${clientId[provider]}&redirect_uri=${redirectUri}&scope=email profile`;

            window.location.href = authUrl;
        }
    </script>
</body>
</html>
