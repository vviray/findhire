<?php
session_start();
require './core/dbConfig.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Validate input
    if (empty($name) || empty($email) || empty($password) || empty($role)) {
        $_SESSION['error'] = "All fields are required.";
        header("Location: register.php");
        exit;
    }

    // Check for valid role
    if (!in_array($role, ['employee', 'admin'])) {
        $_SESSION['error'] = "Invalid role selected.";
        header("Location: register.php");
        exit;
    }

    // Check if email already exists
    $query = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $_SESSION['error'] = "Email is already registered.";
        header("Location: register.php");
        exit;
    }

    $stmt->close();

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user into the database
    $query = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ssss', $name, $email, $hashed_password, $role);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Registration successful. Please log in.";
        header("Location: login.php");
        exit;
    } else {
        $_SESSION['error'] = "Registration failed. Please try again.";
        header("Location: register.php");
        exit;
    }
}

header("Location: register.php"); // Redirect if accessed directly
exit;
