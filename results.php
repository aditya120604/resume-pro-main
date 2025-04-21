<?php
require_once 'config.php';
requireLogin(); // Ensure user is logged in

$analysisId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Get analysis data
$stmt = $conn->prepare("SELECT * FROM resume_analyses WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $analysisId, $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    // Analysis not found or doesn't belong to user
    header("Location: dashboard.php");
    exit();
}

$analysis = $result->fetch_assoc();
$analysisData = json_decode($analysis['analysis_json'], true);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analysis Results - ResumePro</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'header.php'; ?>
    
    <section class="results-section">
        <h2>Your Resume Analysis Results</h2>
        <p>Overall Score: <strong><?php echo $analysis['score']; ?>%</strong></p>
        
        <h3>Suggested Improvements:</h3>
        <ul id="suggestions">
            <?php foreach ($analysisData['improvements'] as $improvement): ?>
                <li><?php echo htmlspecialchars($improvement); ?></li>
            <?php endforeach; ?>
        </ul>
        
        <?php if (isset($analysisData['skills'])): ?>
            <h3>Detected Skills:</h3>
            <div class="skills-cloud">
                <?php foreach ($analysisData['skills'] as $skill): ?>
                    <span class="skill-tag"><?php echo htmlspecialchars($skill); ?></span>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <a href="upload_resume.php" class="cta-button">Upload Another Resume</a>
        <a href="dashboard.php" class="secondary-button">Back to Dashboard</a>
    </section>
    
    <?php include 'footer.php'; ?>
</body>
</html>