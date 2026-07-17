<?php
session_start();
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['name']);  // from form input named 'name'
    $staffId = trim($_POST['staffId']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // Basic validation
    if (empty($username) || empty($staffId) || empty($password) || empty($confirmPassword)) {
        echo "<script>alert('Please fill in all fields.'); window.history.back();</script>";
        exit;
    }

    if ($password !== $confirmPassword) {
        echo "<script>alert('Passwords do not match.'); window.history.back();</script>";
        exit;
    }

    if (strlen($password) < 8 || strlen($password) > 12) {
        echo "<script>alert('Password must be between 8 and 12 characters.'); window.history.back();</script>";
        exit;
    }

    // Check if staffId already exists
    $stmt = $conn->prepare("SELECT staffId FROM Staff WHERE staffId = ?");
    $stmt->bind_param("s", $staffId); 
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        echo "<script>alert('Staff ID already registered. Please use a different Staff ID.'); window.history.back();</script>";
        exit;
    }

    // Insert new staff without hashing password
    $insertStmt = $conn->prepare("INSERT INTO Staff (username, staffId, password) VALUES (?, ?, ?)");
    $insertStmt->bind_param("sss", $username, $staffId, $password);

    if ($insertStmt->execute()) {
        echo "<script>alert('Registration successful! Please login.'); window.location.href = 'staff-login.html';</script>";
        exit;
    } else {
        echo "<script>alert('Registration failed. Please try again later.'); window.history.back();</script>";
        exit;
    }
} else {
    echo "<script>alert('Invalid request.'); window.history.back();</script>";
    exit;
}
?>
