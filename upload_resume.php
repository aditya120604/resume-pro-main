<?php
require_once 'config.php';
requireLogin(); // Ensure user is logged in

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['resume'])) {
    $file = $_FILES['resume'];
    
    // Check for errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $error = "Upload failed with error code: " . $file['error'];
    } else {
        // Validate file type
        $allowedTypes = ['application/pdf', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'text/plain'];
        $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($fileInfo, $file['tmp_name']);
        finfo_close($fileInfo);
        
        if (!in_array($mimeType, $allowedTypes)) {
            $error = "Invalid file type. Please upload a PDF, DOCX, or TXT file.";
        } else {
            // Create unique filename
            $originalName = $file['name'];
            $extension = pathinfo($originalName, PATHINFO_EXTENSION);
            $newFilename = uniqid('resume_') . '.' . $extension;
            $uploadPath = 'uploads/' . $newFilename;
            
            // Save file
            if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                // Call Python backend API for analysis
                $pythonApi = 'http://localhost:5000/api/analyze';
                $jobDescription = isset($_POST['job_description']) ? $_POST['job_description'] : '';
                
                // Prepare data for API call
                $postData = [
                    'file_path' => $uploadPath,
                    'user_id' => $_SESSION['user_id'],
                    'job_description' => $jobDescription
                ];
                
                // Make API call
                $ch = curl_init($pythonApi);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
                curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
                
                $response = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                
                if ($httpCode === 200) {
                    $analysisResult = json_decode($response, true);
                    
                    // Save analysis result to database
                    $score = $analysisResult['score'];
                    $analysisJson = $response;
                    $userId = $_SESSION['user_id'];
                    
                    $stmt = $conn->prepare("INSERT INTO resume_analyses (user_id, filename, original_filename, score, analysis_json) VALUES (?, ?, ?, ?, ?)");
                    $stmt->bind_param("issss", $userId, $newFilename, $originalName, $score, $analysisJson);
                    
                    if ($stmt->execute()) {
                        $analysisId = $stmt->insert_id;
                        // Redirect to results page
                        header("Location: results.php?id=" . $analysisId);
                        exit();
                    } else {
                        $error = "Failed to save analysis result.";
                    }
                } else {
                    $error = "Analysis failed. Please try again.";
                }
            } else {
                $error = "Failed to upload file.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ResumePro - Upload Resume</title>
    <link rel="stylesheet" href="styles.css">
    <script defer src="app.js"></script>
</head>
<style>
    .upload-section {
        margin: 8rem auto;
        text-align: center;
        padding: 2rem;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        max-width: 600px;
    }
    
    .upload-section h2 {
        margin-bottom: 1.5rem;
        font-size: 2rem;
        color: #333;
        font-weight: bold;
    }
    
    .upload-box {
        padding: 2rem;
        border: 2px dashed #ff7e00; /* Adjust to match the orange palette */
        border-radius: 8px;
        background-color: #fff7e6; /* Light complementary orange */
        cursor: pointer;
        text-align: center;
        transition: border-color 0.3s ease, background-color 0.3s ease;
    }
    
    .upload-box:hover {
        border-color: #1b1b19; /* Slightly brighter orange */
        background-color: #fff3cc; /* Lighter complementary tone */
    }
    
    .file-label {
        display: inline-block;
        background-color: #ff7e00; /* Button color */
        color: #fff;
        padding: 0.8rem 2rem;
        border-radius: 6px;
        cursor: pointer;
        font-size: 1rem;
        font-weight: bold;
        text-align: center;
        transition: background-color 0.3s ease;
    }
    
    .file-label:hover {
        background-color: #ffa500; /* Slightly brighter orange on hover */
    }
    
    .file-label input {
        display: none; /* Hides the file input */
    }
    
    .upload-box p {
        font-size: 1rem;
        color: #333;
        margin-top: 1rem;
    }
    
    .analysis-btn {
        display: inline-block;
        margin-top: 1.5rem;
        padding: 0.8rem 2rem;
        font-size: 1rem;
        font-weight: bold;
        color: #fff;
        background-color: #021626; /* Matches the orange palette */
        border: none;
        border-radius: 6px;
        cursor: pointer;
        transition: background-color 0.3s ease, transform 0.2s ease;
    }
    
    .analysis-btn:hover {
        background-color: #333; /* Slightly brighter orange */
        transform: scale(1.05); /* Subtle scaling effect */
    }
    
</style>
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
                    <li><a href="about.html">About</a></li>
                    <!-- <li><a href="login.html">Login</a></li>
                    <li><a href="signup.html">Sign Up</a></li> -->
                </ul>
            </nav>
        </div>
    </header>

    <!-- Upload Section -->
<section class="upload-section">
    <h2>Upload Your File</h2>
    <div class="upload-box">
        <label for="fileInput" class="file-label">
            <span>CHOOSE FILES</span>
            <input type="file" id="fileInput" accept=".pdf,.docx" />
        </label>
        <p>or drop files here</p>
    </div>
    <button id="analyzeBtn" class="analysis-btn" onclick="redirectToAnalysis()">Analyze Resume</button>
    
</section>

<script>
    // Function to redirect to another page
    function redirectToAnalysis() {
        window.location.href = "results.html"; // Change this to your target page
    }
</script>

    <!-- Footer Section -->
    <footer>
        © 2024 ResumePro. All rights reserved.
    </footer>

</body>
</html>
