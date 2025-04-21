// Resume Upload Handling
document.getElementById('resumeUpload').addEventListener('change', function(event) {
    const file = event.target.files[0];
    if (file) {
        // Display filename or handle the resume file
        document.getElementById('file-name').innerText = file.name;
    }
});

// Simulate Resume Analysis on Button Click
document.getElementById('analyzeBtn').addEventListener('click', function() {
    // For now, just simulate analysis and redirect to results
    window.location.href = 'results.html';
});
