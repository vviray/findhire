<?php
session_start();
require './core/dbConfig.php';

// Redirect unauthenticated users to the login page
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php"); // This ensures only logged-in admins can access
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $job_title = $_POST['job_title'];
    $job_description = $_POST['job_description'];

    $stmt = $conn->prepare("INSERT INTO jobs (title, description) VALUES (?, ?)");
    $stmt->bind_param("ss", $job_title, $job_description);

    if ($stmt->execute()) {
        $success = "Job posted successfully!";
    } else {
        $error = "Error posting job. Please try again.";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Job</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Post a Job</h1>
        <?php
        if (isset($success)) echo "<div class='alert alert-success'>$success</div>";
        if (isset($error)) echo "<div class='alert alert-danger'>$error</div>";
        ?>
        <form action="" method="POST" class="mt-4">
            <div class="mb-3">
                <label for="job_title" class="form-label">Job Title</label>
                <input type="text" class="form-control" id="job_title" name="job_title" required>
            </div>
            <div class="mb-3">
                <label for="job_description" class="form-label">Job Description</label>
                <textarea class="form-control" id="job_description" name="job_description" rows="5" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Post Job</button>
        </form>
    </div>
</body>
</html>