<?php
require './core/dbConfig.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $resume = $_FILES['resume']['name'];

    // Save resume file to server
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($resume);
    move_uploaded_file($_FILES['resume']['tmp_name'], $target_file);

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO applications (name, email, resume) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $resume);

    if ($stmt->execute()) {
        echo "Application submitted successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
}
?>